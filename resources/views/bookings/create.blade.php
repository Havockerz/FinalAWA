@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Rent {{ $car->brand }} {{ $car->model }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h5>
            <p class="card-text">
                <strong>License Plate:</strong> {{ $car->license_plate }}<br>
                <strong>Price Per Day:</strong> RM{{ number_format($car->price_per_day, 2) }}<br>
                <strong>Branch:</strong> {{ $car->branch }}<br>
                <strong>Status:</strong> {{ ucfirst($car->status) }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf

        <input type="hidden" name="car_id" value="{{ $car->id }}">

        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" required min="{{ date('Y-m-d') }}">
        </div>

        

        <button type="submit" class="btn btn-success">Confirm Booking</button>
    </form>
</div>
@endsection
