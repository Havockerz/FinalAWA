@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

        <div class="form-group">
    <label for="start_date">Start Date</label>
    <input type="text" id="start_date" name="start_date" class="form-control" required>
    <p class="text-muted">* Bookings must be made at least 2 days in advance.</p>
</div>


<div class="form-group">
    <label for="end_date">End Date</label>
    <input type="text" id="end_date" name="end_date" class="form-control" required>
</div>

<script>
    const bookedRanges = @json($bookedDates->map(function($b) {
        return ['from' => $b->start_date, 'to' => $b->end_date];
    }));

    const twoDaysFromNow = new Date();
    twoDaysFromNow.setDate(twoDaysFromNow.getDate() + 2);

    flatpickr("#start_date", {
        minDate: twoDaysFromNow,
        disable: bookedRanges,
        dateFormat: "Y-m-d"
    });

    flatpickr("#end_date", {
        minDate: twoDaysFromNow,
        disable: bookedRanges,
        dateFormat: "Y-m-d"
    });
</script>




        

        <button type="submit" class="btn btn-success">Confirm Booking</button>
    </form>
</div>

<script>
    const bookedRanges = @json($bookedDates->map(function($b) {
        return ['from' => $b->start_date, 'to' => $b->end_date];
    }));
</script>

@endsection
