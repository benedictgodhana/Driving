<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Instructor; // Adjust this according to your model namespace

class InstructorController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors,email',
            'tel' => 'required|string|max:20', // Example validation
            'id_number' => 'required|string|max:20', // Example validation
            // Add other validation rules as needed
        ]);

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Create a new instructor with user_id
        $instructor = Instructor::create(array_merge($validated, ['user_id' => $userId]));

        // Optionally return a response

    }


    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors,email,' . $id,
            'tel' => 'required|string|max:20', // Example validation
            'id_number' => 'required|string|max:20', // Example validation
        ]);

        $instructor = Instructor::findOrFail($id);

        $userId = Auth::id();

        $instructor->update(array_merge($validated, ['user_id' => $userId]));


    }

    public function destroy($id)
    {
        $instructor = Instructor::find($id);
        if ($instructor) {
            $instructor->delete(); // This performs a soft delete
        }
    }
}
