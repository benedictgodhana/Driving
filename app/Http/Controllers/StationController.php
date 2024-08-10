<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StationController extends Controller
{

public function create (Request $request)
{
    $validatedData = $request->validate([
        'station_code' => 'required|string|max:255',
        'station_name' => 'required|string|max:255',
        'station_phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ]);


    \App\Models\Station::create($validatedData);

    return redirect()->route('stations.create')->with('success', 'Station created successfully!');
}

}
