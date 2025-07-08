<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->allTeams();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'members' => 'required|array|min:1',
            'members.*.name' => 'required|string|max:255',
            'members.*.student_number' => 'required|string|max:20'
        ]);

        DB::beginTransaction();
        try {
            $team = Team::create([
                'name' => $request->name,
                'leader_id' => Auth::id(),
                'member_count' => count($request->members) + 1,
            ]);

            foreach ($request->members as $member) {
                TeamMember::create([
                    'team_id' => $team->id,
                    'name' => $member['name'],
                    'student_number' => $member['student_number'],
                    'phone' => $member['phone'] ?? null,
                    'role' => 'member'
                ]);
            }

            TeamMember::create([
                'team_id' => $team->id,
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'student_number' => Auth::user()->student_number,
                'phone' => Auth::user()->phone,
                'role' => 'co_leader'
            ]);

            DB::commit();
            return redirect()->route('teams.index')
                ->with('success', 'تیم جدید با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در ایجاد تیم: ' . $e->getMessage());
        }
    }

    public function show(Team $team)
    {
        $user = Auth::user();
        $isLeader = $team->leader_id === $user->id;
        $isMember = $team->members()->where('user_id', $user->id)->exists();
        
        if (!($isLeader || $isMember)) {
            abort(403, 'شما مجاز به مشاهده این تیم نیستید.');
        }
        
        $team->load('members');
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $user = Auth::user();
        if ($team->leader_id !== $user->id) {
            abort(403, 'شما مجاز به ویرایش این تیم نیستید.');
        }
        
        $team->load('members');
        return view('teams.edit', [
            'team' => $team,
            'members' => $team->members
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $user = Auth::user();
        if ($team->leader_id !== $user->id) {
            abort(403, 'شما مجاز به ویرایش این تیم نیستید.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,'.$team->id,
            'leader_id' => 'required|exists:team_members,id,team_id,'.$team->id,
            'new_members' => 'sometimes|array',
            'new_members.*.name' => 'required_with:new_members|string|max:255',
            'new_members.*.student_number' => 'required_with:new_members|string|max:20',
            'remove_members' => 'sometimes|array',
            'remove_members.*' => 'exists:team_members,id,team_id,'.$team->id
        ]);

        DB::beginTransaction();
        try {
            // Update team name
            $team->update(['name' => $request->name]);

            // Change team leader if needed
            $newLeader = TeamMember::findOrFail($request->leader_id);
            if ($newLeader->role !== 'leader') {
                // Remove leader role from current leader
                TeamMember::where('team_id', $team->id)
                         ->where('role', 'leader')
                         ->update(['role' => 'member']);
                
                // Set new leader
                $newLeader->update(['role' => 'leader']);
                $team->update(['leader_id' => $newLeader->user_id ?? $newLeader->id]);
            }

            // Add new members
            if ($request->has('new_members')) {
                foreach ($request->new_members as $member) {
                    TeamMember::create([
                        'team_id' => $team->id,
                        'name' => $member['name'],
                        'student_number' => $member['student_number'],
                        'phone' => $member['phone'] ?? null,
                        'role' => 'member'
                    ]);
                }
                $team->increment('member_count', count($request->new_members));
            }

            // Remove members
            if ($request->has('remove_members')) {
                TeamMember::whereIn('id', $request->remove_members)
                          ->where('role', '!=', 'leader')
                          ->delete();
                $team->decrement('member_count', count($request->remove_members));
            }

            DB::commit();
            return redirect()->route('teams.show', $team->id)
                ->with('success', 'تغییرات تیم با موفقیت ذخیره شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در ذخیره تغییرات: ' . $e->getMessage());
        }
    }

    public function destroy(Team $team)
    {
        $user = Auth::user();
        if ($team->leader_id !== $user->id) {
            abort(403, 'شما مجاز به حذف این تیم نیستید.');
        }

        if ($team->reservations()->exists()) {
            return back()->with('error', 'این تیم در رزروها استفاده شده و قابل حذف نیست.');
        }

        DB::beginTransaction();
        try {
            $team->members()->delete();
            $team->delete();
            DB::commit();
            return redirect()->route('teams.index')
                ->with('success', 'تیم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در حذف تیم: ' . $e->getMessage());
        }
    }

    public function addMember(Request $request, Team $team)
    {
        $user = Auth::user();
        if ($team->leader_id !== $user->id) {
            abort(403, 'شما مجاز به افزودن عضو به این تیم نیستید.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20'
        ]);

        DB::beginTransaction();
        try {
            TeamMember::create([
                'team_id' => $team->id,
                'name' => $request->name,
                'student_number' => $request->student_number,
                'phone' => $request->phone,
                'role' => 'member'
            ]);

            $team->increment('member_count');
            DB::commit();
            return back()->with('success', 'عضو جدید با موفقیت اضافه شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در افزودن عضو: ' . $e->getMessage());
        }
    }

    public function removeMember(Team $team, TeamMember $member)
    {
        $user = Auth::user();
        if ($team->leader_id !== $user->id) {
            abort(403, 'شما مجاز به حذف عضو از این تیم نیستید.');
        }

        if ($member->role === 'leader') {
            return back()->with('error', 'نمی‌توانید رهبر تیم را حذف کنید.');
        }

        DB::beginTransaction();
        try {
            $member->delete();
            $team->decrement('member_count');
            DB::commit();
            return back()->with('success', 'عضو با موفقیت حذف شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در حذف عضو: ' . $e->getMessage());
        }
    }
}