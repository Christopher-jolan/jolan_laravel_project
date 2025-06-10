<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\GymSession;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function create(GymSession $gymSession)
    {
        if ($gymSession->status === 'full') {
            return redirect()->back()->with('error', 'این سانس تکمیل ظرفیت شده است.');
        }

        $user = auth()->user();
        $userTeams = $user->allTeams();

        return view('reservations.create', [
            'gymSession' => $gymSession,
            'userTeams' => $userTeams,
            'canCreateTeam' => $user->ownedTeams()->count() < 3
        ]);
    }

    public function store(Request $request, GymSession $gymSession)
    {
        DB::beginTransaction();

        try {
            // اعتبارسنجی اولیه
            $request->validate([
                'reservation_type' => 'required|in:individual,team'
            ]);

            // پردازش بر اساس نوع رزرو
            if ($request->reservation_type === 'team') {
                return $this->processTeamReservation($request, $gymSession);
            }

            return $this->processIndividualReservation($request, $gymSession);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    protected function processTeamReservation($request, $gymSession)
    {
        // اعتبارسنجی تیم
        if ($request->has('team_id')) {
            $request->validate([
                'team_id' => 'required|exists:teams,id'
            ]);

            $team = Team::findOrFail($request->team_id);
            if ($team->leader_id !== auth()->id()) {
                throw new \Exception('شما مجاز به رزرو با این تیم نیستید');
            }

            $memberCount = $team->member_count;
        } else {
            $request->validate([
                'team_name' => 'required_if:reservation_type,team|string|max:255|unique:teams,name',
                'members' => 'required_if:reservation_type,team|array|min:1',
                'members.*.name' => 'required_if:reservation_type,team|string|max:255',
                'members.*.student_number' => 'required_if:reservation_type,team|string|max:20'
            ]);

            $team = $this->createTeam($request);
            $memberCount = count($request->members) + 1;
        }

        // ایجاد رزرو
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'gym_session_id' => $gymSession->id,
            'team_id' => $team->id,
            'team_name' => $team->name,
            'member_count' => $memberCount,
            'status' => 'pending',
            'notes' => $request->notes ?? null
        ]);

        // به‌روزرسانی ظرفیت
        $gymSession->increment('reserved_count', $memberCount);
        $gymSession->updateStatus();

        DB::commit();

        return redirect()->route('dashboard')
            ->with('success', 'رزرو تیمی با موفقیت ثبت شد');
    }

    protected function processIndividualReservation($request, $gymSession)
    {
        // اعتبارسنجی رزرو انفرادی
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        // ایجاد رزرو
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'gym_session_id' => $gymSession->id,
            'member_count' => 1,
            'status' => 'pending',
            'notes' => $request->notes ?? null
        ]);

        // به‌روزرسانی ظرفیت
        $gymSession->increment('reserved_count');
        $gymSession->updateStatus();

        DB::commit();

        return redirect()->route('dashboard')
            ->with('success', 'رزرو انفرادی با موفقیت ثبت شد');
    }

    protected function createTeam($request)
    {
        $team = Team::create([
            'name' => $request->team_name,
            'leader_id' => auth()->id(),
            'member_count' => count($request->members) + 1
        ]);

        // افزودن اعضا
        foreach ($request->members as $member) {
            TeamMember::create([
                'team_id' => $team->id,
                'name' => $member['name'],
                'student_number' => $member['student_number'],
                'phone' => $member['phone'] ?? null,
                'role' => 'member'
            ]);
        }

        // افزودن رهبر تیم
        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'student_number' => auth()->user()->student_number,
            'phone' => auth()->user()->phone,
            'role' => 'leader'
        ]);

        return $team;
    }

    

    protected function createReservation(Request $request, GymSession $gymSession)
    {
        return Reservation::create([
            'user_id' => auth()->id(),
            'gym_session_id' => $gymSession->id,
            'team_id' => $request->reservation_type === 'team' ? $request->team_id : null,
            'team_name' => $request->reservation_type === 'team' ? Team::find($request->team_id)->name : null,
            'member_count' => $request->reservation_type === 'team' ? $request->member_count : 1,
            'status' => 'pending',
            'notes' => $request->notes
        ]);
    }

    protected function updateSessionCapacity(GymSession $gymSession, $memberCount)
    {
        $gymSession->increment('reserved_count', $memberCount);
        $gymSession->update(['status' => $gymSession->reserved_count >= $gymSession->max_capacity ? 'full' : 'reserved']);
    }

    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reservations.show', [
            'reservation' => $reservation->load(['gymSession', 'team.members'])
        ]);
    }

    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'این رزرو قابل ویرایش نیست');
        }

        return view('reservations.edit', [
            'reservation' => $reservation,
            'userTeams' => auth()->user()->allTeams(),
            'canCreateTeam' => auth()->user()->canCreateTeam()
        ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        DB::beginTransaction();
        
        try {
            if ($reservation->user_id !== auth()->id()) {
                abort(403);
            }

            if (!in_array($reservation->status, ['pending', 'confirmed'])) {
                throw new \Exception('این رزرو قابل ویرایش نیست');
            }

            $validated = $request->validate([
                'reservation_type' => 'required|in:individual,team',
                'team_id' => 'required_if:reservation_type,team|exists:teams,id',
                'member_count' => [
                    'required_if:reservation_type,team',
                    'integer',
                    'min:2',
                    'max:' . ($reservation->gymSession->max_capacity - $reservation->gymSession->reserved_count + $reservation->member_count)
                ],
                'notes' => 'nullable|string|max:500'
            ]);

            // محاسبه تغییرات ظرفیت
            $newCount = $request->reservation_type === 'team' ? $request->member_count : 1;
            $capacityChange = $newCount - $reservation->member_count;

            // به‌روزرسانی رزرو
            $reservation->update([
                'team_id' => $request->reservation_type === 'team' ? $request->team_id : null,
                'team_name' => $request->reservation_type === 'team' ? Team::find($request->team_id)->name : null,
                'member_count' => $newCount,
                'notes' => $request->notes,
                'status' => 'pending' // نیاز به تایید مجدد
            ]);

            // به‌روزرسانی ظرفیت سانس
            $reservation->gymSession->increment('reserved_count', $capacityChange);
            $reservation->gymSession->updateStatus();

            DB::commit();

            return redirect()->route('reservations.show', $reservation->id)
                ->with('success', 'رزرو با موفقیت بروزرسانی شد');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function cancel(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(403, 'شما مجاز به لغو این رزرو نیستید.');
        }

        if ($reservation->status === 'cancelled') {
            return back()->with('error', 'این رزرو قبلاً لغو شده است.');
        }

        $sessionTime = Carbon::parse($reservation->gymSession->date.' '.$reservation->gymSession->start_time);
        if ($sessionTime->diffInHours(now()) < 12) {
            return back()->with('error', 'زمان لغو این رزرو به پایان رسیده است.');
        }

        $reservation->update(['status' => 'cancelled']);
        $reservation->gymSession->updateStatus();

        return back()->with('success', 'رزرو با موفقیت لغو شد.');
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(403, 'شما مجاز به حذف این رزرو نیستید.');
        }

        if ($reservation->status !== 'cancelled') {
            return back()->with('error', 'فقط رزروهای لغو شده قابل حذف هستند.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'رزرو با موفقیت حذف شد.');
    }

    public function teamReservations(Request $request)
    {
        $teamReservations = Reservation::whereHas('team', function($query) use ($request) {
            $query->where('leader_id', $request->user()->id);
        })
        ->with(['gymSession', 'team', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('reservations.team-reservations', compact('teamReservations'));
    }

    public function joinRequests()
    {
        $joinRequests = JoinRequest::whereHas('reservation', function($query) {
            $query->where('user_id', auth()->id());
        })->with(['user', 'reservation.gymSession'])->get();

        return view('join_requests.index', compact('joinRequests'));
    }
}