<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\WelcomeEmail;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'nullable|string|max:255',
            'admission_number' => 'nullable|string|max:255',
            // Add validation rules for user profile fields
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'year_of_study' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'gender' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'admission_number' => $request->admission_number,
            'password' => Hash::make($request->password),        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('signedPdf')) {
            $filePath = $request->file('signedPdf')->store('signed_pdfs', 'public');
        }

        $userProfile = UserProfile::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'course' => $request->course,
            'year_of_study' => $request->year_of_study,
            'faculty' => $request->faculty,
            'bio' => $request->bio,
            'gender' => $request->gender,
            'religion' => $request->religion,
        ]);

        // Assign the "member" role to the user
        $role = Role::where('name', 'student')->first();
        if ($role) {
            $user->assignRole($role);
        }

        event(new Registered($user));

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));

    }
}
