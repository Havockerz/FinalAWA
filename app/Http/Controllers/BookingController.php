<?php

namespace App\Http\Controllers;


use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

public function index()
    {
        // Get the logged-in staff's branch
        $branch = Auth::user()->branch;

        // Fetch bookings where the car's branch matches the logged-in staff's branch
        $bookings = Booking::whereHas('car', function ($query) use ($branch) {
            $query->where('branch', $branch);
        })->get();

        // Pass bookings to the view
        return view('bookings.index', compact('bookings'));
    }

    public function create($carId)
{
    $car = \App\Models\Car::findOrFail($carId);

    // Optionally, check if the car is available
    if ($car->status !== 'available') {
        return redirect()->back()->with('error', 'This car is not available for rent.');
    }

    return view('bookings.create', compact('car'));
}

public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            
        ]);

        // Create a new booking
        $booking = new Booking();
        $booking->user_id = Auth::id();  // Get the logged-in user ID
        $booking->car_id = $request->car_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        
        $booking->status = 'pending';  // Set default status
        $booking->save();

        // Redirect to a success page with a success message
        return redirect()->route('cars.index')->with('success', 'Booking confirmed successfully!');
    }

}
