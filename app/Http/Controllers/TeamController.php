<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function store(CreateTeamRequest $request)
    {
        $user = Auth::user();

        // ایجاد تیم جدید
        $team = Team::create([
            'name' => $request->name,
            'leader_id' => $user->id,
            'member_count' => count($request->members) + 1, // +1 برای رهبر تیم
        ]);

        // افزودن اعضای تیم
        foreach ($request->members as $member) {
            TeamMember::create([
                'team_id' => $team->id,
                'user_id' => null, // برای اعضای غیرسیستمی null می‌گذاریم
                'name' => $member['name'],
                'email' => $member['email'] ?? null,
                'phone' => $member['phone'] ?? null,
                'role' => 'member',
            ]);
        }

        // افزودن کاربر فعلی به عنوان رهبر تیم
        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => 'leader',
        ]);

        return redirect()->back()
            ->with('success', 'تیم جدید با موفقیت ایجاد شد.')
            ->with('new_team_id', $team->id);
    }
}