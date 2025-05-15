@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bookings for Branch: {{ $bookings->first() ? $bookings->first()->car->branch : 'N/A' }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($bookings->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Customer</th>
                    <th>Rent From</th>
                    <th>Rent Until</th>
                    <th>Status</th>
                    <th>Action</th> {{-- New column --}}
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->car->brand }} {{ $booking->car->model }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($booking->status) }}</td>
                        <td>
    @if($booking->status === 'pending')
        <div class="d-flex gap-1">
            <form action="{{ route('bookings.approve', $booking->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Approve</button>
            </form>
            <form action="{{ route('bookings.reject', $booking->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
            </form>
        </div>
    @else
        <span class="text-muted">—</span>
    @endif
</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No bookings found for your branch.</p>
    @endif
</div>
@endsection
