<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;  
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class CarController extends Controller
{
    public function index(Request $request)
{
    $query = Car::query();

    // Filter by branch
    if ($request->filled('branch')) {
        $query->where('branch', $request->branch);
    }

    // Search by brand or model
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('brand', 'like', "%$search%")
              ->orWhere('model', 'like', "%$search%");
        });
    }

    $cars = $query->get();

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
        'license_plate' => 'required|string|max:20|unique:cars',
        'price_per_day' => 'required|numeric',
        'description' => 'nullable|string',
        'picture' => 'nullable|image|max:2048',  // Validate image file
    ]);

    $staff = Staff::where('user_id', auth()->id())->first();
    if (!$staff) {
        return back()->with('error', 'Only staff can add cars.');
    }

    $branch = $staff->branch;

    // Handle picture upload if exists
    $picturePath = null;
    if ($request->hasFile('picture')) {
        $picturePath = $request->file('picture')->store('car_pictures', 'public');
    }

    Car::create([
        'brand' => $request->brand,
        'model' => $request->model,
        'year' => $request->year,
        'license_plate' => $request->license_plate,
        'price_per_day' => $request->price_per_day,
        'branch' => $branch,
        'status' => 'available',
        'staff_id' => $staff->id,
        'description' => $request->description,
        'picture' => $picturePath,
    ]);

    return redirect()->route('cars.index')->with('success', 'Car added successfully.');
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
    $request->validate([
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer',
        'price_per_day' => 'required|numeric',
        'license_plate' => 'required|string|unique:cars,license_plate,' . $carId,
        'description' => 'nullable|string',
        'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Validate image if uploaded
    ]);

    $car = Car::findOrFail($carId);

    $data = [
        'brand' => $request->brand,
        'model' => $request->model,
        'year' => $request->year,
        'price_per_day' => $request->price_per_day,
        'license_plate' => $request->license_plate,
        'description' => $request->description,
    ];

    if ($request->hasFile('picture')) {
        // Delete old picture if exists
        if ($car->picture && \Storage::disk('public')->exists($car->picture)) {
            \Storage::disk('public')->delete($car->picture);
        }

        // Store new picture and update path in data
        $path = $request->file('picture')->store('car_pictures', 'public');
        $data['picture'] = $path;
    }

    $car->update($data);

    return redirect()->route('cars.index')->with('success', 'Car details updated successfully!');
}







}
