@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Bookings</h1>

    @if($bookings->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Rent From</th>
                    <th>Rent Until</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->car->brand }} {{ $booking->car->model }} ({{ $booking->car->license_plate }})</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($booking->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>You have no bookings yet.</p>
    @endif
</div>
@endsection
