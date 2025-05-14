@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Customer Bookings</h1>

    @if($bookings->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Car</th>
                        <th>Price/Day</th>
                        <th>Booking Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->car->brand }} {{ $booking->car->model }} ({{ $booking->car->year }})</td>
                            <td>RM{{ number_format($booking->car->price_per_day, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</td>
                            <td>{{ ucfirst($booking->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No bookings found for your branch.</p>
    @endif
</div>
@endsection
