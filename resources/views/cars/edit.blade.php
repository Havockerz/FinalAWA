@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Car Details</h1>

    <form action="{{ route('cars.update', $car->id) }}" method="POST">
        @csrf
        @method('PUT')


         <div class="form-group mb-3">
        <label for="picture">Car Picture</label>
        @if($car->picture)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $car->picture) }}" alt="Car Picture" style="max-width: 200px;">
            </div>
        @endif
        <input type="file" name="picture" id="picture" class="form-control">
        <small class="form-text text-muted">Upload a new picture to replace the current one.</small>
    </div>
    
        <div class="form-group mb-3">
            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand', $car->brand) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="model">Model</label>
            <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $car->model) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $car->year) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="price_per_day">Price Per Day</label>
            <input type="number" name="price_per_day" id="price_per_day" class="form-control" value="{{ old('price_per_day', $car->price_per_day) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="license_plate">License Plate</label>
            <input type="text" name="license_plate" id="license_plate" class="form-control" value="{{ old('license_plate', $car->license_plate) }}" required>
        </div>

        <!-- Branch field, displayed but not editable by staff -->
        <div class="form-group mb-3">
            <label for="branch">Branch</label>
            <!-- Make branch read-only by disabling the input or using a plain text -->
            <input type="text" name="branch" id="branch" class="form-control" value="{{ old('branch', $car->branch) }}" disabled>
            <!-- Or display it as plain text -->
            <!-- <p class="form-control-plaintext">{{ $car->branch }}</p> -->
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $car->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update Car</button>
        <a href="{{ route('cars.index') }}" class="btn btn-secondary mt-2">Back to Car List</a>

    </form>
</div>
@endsection
