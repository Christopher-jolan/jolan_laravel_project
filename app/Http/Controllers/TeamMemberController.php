<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20'
        ]);

        // بررسی مالکیت تیم
        $team = Team::findOrFail($request->team_id);
        if ($team->leader_id !== auth()->id()) {
            abort(403, 'شما مجاز به اضافه کردن عضو به این تیم نیستید.');
        }

        $member = TeamMember::create([
            'team_id' => $request->team_id,
            'name' => $request->name,
            'student_number' => $request->student_number,
            'phone' => $request->phone,
            'role' => 'member'
        ]);

        // افزایش تعداد اعضای تیم
        $team->increment('member_count');

        return redirect()->back()->with('success', 'عضو جدید با موفقیت اضافه شد.');
    }

    public function destroy(TeamMember $teamMember)
    {
        // بررسی مالکیت تیم
        if ($teamMember->team->leader_id !== auth()->id()) {
            abort(403, 'شما مجاز به حذف عضو از این تیم نیستید.');
        }

        // جلوگیری از حذف رهبر تیم
        if ($teamMember->role === 'leader') {
            return back()->with('error', 'نمی‌توانید رهبر تیم را حذف کنید.');
        }

        $teamMember->delete();
        
        // کاهش تعداد اعضای تیم
        $teamMember->team->decrement('member_count');

        return back()->with('success', 'عضو با موفقیت حذف شد.');
    }
}