<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ContributionController extends Controller
{
    public function index()
{
    // Retrieve the authenticated user
    $user = Auth::user();

    // Retrieve contributions associated with the authenticated user

    // Return the contributions as JSON
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'code' => 'required|string|max:255',
        ]);

        $contribution = new Contribution($validated);
        $request->user()->contributions()->save($contribution);

        return redirect()->route('contributions.index')->with('success', 'Contribution recorded successfully.');
    }





}
