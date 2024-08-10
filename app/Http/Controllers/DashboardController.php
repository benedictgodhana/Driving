<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Application;
use App\Models\UserProfile;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch applications for the authenticated user with additional details
        $applications = Application::with(['applicationType', 'location', 'institution', 'activity'])
            ->where('user_id', Auth::id())
            ->get();

        // Fetch the user profile for the authenticated user
        $userProfile = UserProfile::where('user_id', Auth::id())->first();
        

        // Pass the applications and user profile data to the Inertia view
        return Inertia::render('Dashboard', [
            'applications' => $applications,
            'userProfile' => $userProfile,
        ]);
    }
}
