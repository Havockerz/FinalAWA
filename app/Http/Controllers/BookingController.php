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
    // Get logged-in user's staff record
    $staff = \App\Models\Staff::where('user_id', Auth::id())->first();

    if (!$staff) {
        return redirect()->back()->with('error', 'You are not authorized to view bookings.');
    }

    $branch = $staff->branch;

    // Get bookings for cars in this branch, eager load car and user
    $bookings = Booking::whereHas('car', function ($query) use ($branch) {
        $query->where('branch', $branch);
    })->with(['car', 'user'])->get();

    return view('bookings.index', compact('bookings'));
}


    public function create($carId)
{
    $car = Car::findOrFail($carId);

    if ($car->status !== 'available') {
        return redirect()->back()->with('error', 'This car is not available for rent.');
    }

    // Get all booked date ranges for this car
    $bookedDates = $car->bookings()
        ->whereIn('status', ['pending', 'confirmed']) // only block active bookings
        ->get(['start_date', 'end_date']);

    return view('bookings.create', compact('car', 'bookedDates'));
}


public function store(Request $request)
{
    $request->validate([
        'car_id' => 'required|exists:cars,id',
        'start_date' => 'required|date|after_or_equal:' . now()->addDays(2)->toDateString(),
        'end_date' => 'required|date|after:start_date',
    ]);

    $userId = Auth::id();

    // Check if user has 2 or more bookings that overlap with this rental period
    $existingBookings = \App\Models\Booking::where('user_id', $userId)
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                  });
        })
        ->count();

    if ($existingBookings >= 2) {
        return redirect()->back()->with('error', 'You can only book up to 2 cars for the same rental period.');
    }

    $booking = new Booking();
    $booking->user_id = $userId;
    $booking->car_id = $request->car_id;
    $booking->start_date = $request->start_date;
    $booking->end_date = $request->end_date;
    $booking->status = 'pending';
    $booking->save();

    return redirect()->route('cars.index')->with('success', 'Booking confirmed successfully!');
}


    public function approve($bookingId)
{
    $booking = Booking::findOrFail($bookingId);

    // Optional: Check if the logged-in user is authorized to approve this booking
    $staff = \App\Models\Staff::where('user_id', Auth::id())->first();
    if (!$staff || $booking->car->branch !== $staff->branch) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $booking->status = 'confirmed';
    $booking->save();

    return redirect()->back()->with('success', 'Booking confirmed successfully.');
}

public function myBookings()
{
    $userId = auth()->id();

    // Get bookings of the logged-in customer with car details
    $bookings = Booking::with('car')
                ->where('user_id', $userId)
                ->orderBy('start_date', 'desc')
                ->get();

    return view('bookings.my', compact('bookings'));
}

public function reject(Booking $booking)
{
    // Optional: Check if the current user is staff
    if (Auth::user()->role !== 'staff') {
        abort(403);
    }

    if ($booking->status === 'pending') {
        $booking->status = 'rejected';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been rejected.');
    }

    return redirect()->back()->with('error', 'Only pending bookings can be rejected.');
}




}
