<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Log;
use App\Notifications\ApplicationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationApproved;
use App\Mail\ApplicationSubmitted as ApplicationSubmittedMail;

class ApplicationController extends Controller
{

    public function submit(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'applicationType' => 'required|exists:application_types,id',
        'description' => 'required|string',
        'preferredStartDate' => 'required|date',
        'cover_letter' => 'required|file|mimes:pdf,doc,docx',
        'location' => 'required|exists:locations,id',
        'institution' => 'required|exists:institutions,id',
        'activity' => 'required|exists:activities,id',
    ]);

    // Handle file upload
    if ($request->hasFile('cover_letter')) {
        $validated['cover_letter'] = $request->file('cover_letter')->store('cover_letters', 'public');
    }

    // Map the validated data to match the database schema
    $applicationData = [
        'application_type_id' => $validated['applicationType'],
        'description' => $validated['description'],
        'preferred_start_date' => $validated['preferredStartDate'],
        'cover_letter' => $validated['cover_letter'],
        'location_id' => $validated['location'],
        'institution_id' => $validated['institution'],
        'activity_id' => $validated['activity'],
        'user_id' => Auth::id(),
    ];

    // Handle the form submission: Create a new application in the database
    $application = Application::create($applicationData);

    // Redirect or return a response
    return redirect()->back()->with('success', 'Application submitted successfully.');}


    protected function sendNotificationAndEmail($user, $application)
    {
        // Create notification
        $notification = [
            'title' => 'Application Submitted',
            'message' => 'Your application with ID ' . $application->id . ' has been successfully submitted.',
        ];

        // Save notification to the database
        $user->notifications()->create($notification);

        // Send notification via email
        Notification::route('mail', $user->email)
            ->notify(new ApplicationSubmitted($application));

        // Or using Mail facade directly
        Mail::to($user->email)->send(new ApplicationSubmittedMail($application));
    }



    public function update(Request $request)
{
    $application = Application::find($request->id);
    if ($application) {
        $application->status = $request->status;
        $application->save();
        return response()->json(['message' => 'Application status updated successfully'], 200);
    }
    return response()->json(['message' => 'Application not found'], 404);
}

public function approve(Request $request)
{
    $application = Application::with(['applicationType', 'user', 'institution', 'activity'])->find($request->id);

    if ($application) {
        $application->status = 'Approved';
        $application->save();

        // Send email notification
        Mail::to($application->user->email)->send(new ApplicationApproved($application));

        return response()->json(['message' => 'Application approved successfully'], 200);
    }

    return response()->json(['message' => 'Application not found'], 404);
}



}
