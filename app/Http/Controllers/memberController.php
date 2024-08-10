<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Institution;
use App\Models\ApplicationType;
use App\Models\Activity;
class memberController extends Controller
{

        public function new_application()
        {
            // Fetch locations
            $locations = Location::all();

            // Fetch institutions with their application types
            $institutions = Institution::with('applicationTypes')->get();


            // Fetch other data
            $applicationTypes = ApplicationType::all();
            $activities = Activity::all();

            // Pass data to the Vue component via Inertia
            return Inertia::render('StudentApplication', [
                'locations' => $locations,
                'institutions' => $institutions,
                'applicationTypes' => $applicationTypes,
                'activities' => $activities,
            ]);
        }


    public function getFilteredInstitutions(Request $request)
    {
        $location = $request->query('location');
        $applicationTypeId = $request->query('application_type');

        $institutions = Institution::whereHas('applicationTypes', function($query) use ($applicationTypeId) {
            $query->where('id', $applicationTypeId);
        })
        ->where('location', $location)
        ->get();

        return response()->json($institutions);
    }
    public function notifications()
    {
        return Inertia::render('MemberNotifications');
    }

    public function settings()
    {
        return Inertia::render('Membersettings');
    }

}
