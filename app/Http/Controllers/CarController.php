<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;  
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index(Request $request)
    {
        // Get the branch from the query string, if present
        $branch = $request->get('branch');

        // If a branch is selected, filter the cars by the branch
        if ($branch) {
            $cars = Car::where('branch', $branch)->get();
        } else {
            // Otherwise, get all cars
            $cars = Car::all();
        }

        return view('cars.index', compact('cars'));
    }

public function create()
    {
        return view('cars.create');
    }

    // Store the new car in the database
    public function store(Request $request)
{
    $request->validate([
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer',
        'price_per_day' => 'required|numeric',
        'license_plate' => 'required|string|unique:cars',
        'description' => 'nullable|string',
    ]);

    $user = Auth::user();
    $branch = $user->staff->branch ?? 'Unknown'; // fallback in case no relation

    Car::create([
        'brand' => $request->brand,
        'model' => $request->model,
        'year' => $request->year,
        'price_per_day' => $request->price_per_day,
        'license_plate' => $request->license_plate,
        'branch' => $branch, // ✅ Set from logged-in staff
        'description' => $request->description,
    ]);

    return redirect()->route('cars.create')->with('success', 'Car added successfully!');
}

public function edit($carId)
{
    // Fetch car from the database
    $car = Car::findOrFail($carId);

    // Pass the car data to the view
    return view('cars.edit', compact('car'));
}

public function update(Request $request, $carId)
{
    // Validate the form data, excluding 'branch' from validation
    $request->validate([
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer',
        'price_per_day' => 'required|numeric',
        'license_plate' => 'required|string|unique:cars,license_plate,' . $carId,
        'description' => 'nullable|string',
        // No need to validate 'branch' since it's read-only
    ]);

    // Find the car and update its data, excluding the branch field
    $car = Car::findOrFail($carId);
    $car->update([
        'brand' => $request->brand,
        'model' => $request->model,
        'year' => $request->year,
        'price_per_day' => $request->price_per_day,
        'license_plate' => $request->license_plate,
        'description' => $request->description,
        // Do not update the branch, it remains the same as it was
    ]);

    // Redirect back to the cars index page with success message
    return redirect()->route('cars.index')->with('success', 'Car details updated successfully!');
}




}
