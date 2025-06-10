<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'student_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function rules($userId = null)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$userId,
            'phone' => [
                'required',
                'string',
                'size:11',
                'starts_with:09',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,user',
            'student_number' => 'required|string|size:10|unique:users,student_number,'.$userId,
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

     public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * تیم‌هایی که کاربر مالک آن‌هاست (رهبر تیم است)
     */
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    /**
     * دریافت تمام تیم‌های مرتبط با کاربر (هم به عنوان عضو و هم به عنوان رهبر)
     */
    public function allTeams()
    {
        return Team::where('leader_id', $this->id)
            ->orWhereHas('members', function($query) {
                $query->where('user_id', $this->id);
            })
            ->get();
    }

    public function canCreateTeam()
    {
        // مثلاً محدودیت ایجاد تیم: کاربر می‌تواند حداکثر 3 تیم ایجاد کند
        return $this->ownedTeams()->count() < 3;
    }
}