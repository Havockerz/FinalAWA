@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Available Cars</h1>

    {{-- Filter Form --}}
    {{-- Filter Form --}}
<form action="{{ route('cars.index') }}" method="GET" class="mb-4">
    <div class="row">
        {{-- Branch filter --}}
        <div class="col-md-4">
            <select name="branch" class="form-control">
                <option value="">All Branches</option>
                <option value="Bandar Baru Bangi" {{ request('branch') === 'Bandar Baru Bangi' ? 'selected' : '' }}>Bandar Baru Bangi</option>
                <option value="Shah Alam" {{ request('branch') === 'Shah Alam' ? 'selected' : '' }}>Shah Alam</option>
                <option value="Gombak" {{ request('branch') === 'Gombak' ? 'selected' : '' }}>Gombak</option>
            </select>
        </div>

        {{-- Search bar --}}
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by brand or model..." value="{{ request('search') }}">
        </div>

        {{-- Submit button --}}
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($cars->count() > 0)
        <div class="row">
            @foreach($cars as $car)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            @if($car->picture)
    <img src="{{ asset('storage/' . $car->picture) }}" alt="Car Picture" class="img-fluid mb-2" style="max-height: 150px;">
@endif

                            <h5 class="card-title">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h5>
                            <p class="card-text">
                                <strong>License Plate:</strong> {{ $car->license_plate }}<br>
                                <strong>Price/Day:</strong> RM{{ number_format($car->price_per_day, 2) }}<br>
                                <strong>Branch:</strong> {{ $car->branch }}<br>
                                <strong>Status:</strong> {{ ucfirst($car->status) }}
                            </p>

                            @if($car->description)
                                <p class="card-text text-muted">{{ $car->description }}</p>
                            @endif

                            {{-- Staff Edit Button --}}
                            @if(Auth::check() && Auth::user()->role === 'staff')
                                <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-primary mt-2">Edit</a>
                            @endif

                            {{-- Customer Rent Button --}}
                            @if(Auth::check() && Auth::user()->role === 'customer' && $car->status === 'available')
                                <form action="{{ route('bookings.create', $car->id) }}" method="GET" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">Rent Car</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No cars have been added yet.</p>
    @endif
</div>
@endsection
