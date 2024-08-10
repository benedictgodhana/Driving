<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'station'])->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'username' => $user->username,
                'telephone' => $user->telephone,
                'station_name' => $user->station ? $user->station->station_name : 'N/A', // Ensure station_name is being set
                'roles' => $user->roles->pluck('name')->implode(', '),
            ];
        });

        // Debug: log users to check station_name
        // Log::info($users);

        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }


}
