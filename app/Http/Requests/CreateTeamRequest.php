<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class CreateTeamRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:teams,name',
            'members' => 'required|array|min:1',
            'members.*.name' => 'required|string|max:255',
            'members.*.email' => 'nullable|email|max:255',
            'members.*.phone' => 'nullable|string|max:20',
        ];
    }
}