@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add a New Car</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
        <label for="picture" class="form-label">Car Picture</label>
        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
    </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Car Brand</label>
            <input type="text" class="form-control" id="brand" name="brand" required>
        </div>

        <div class="mb-3">
            <label for="model" class="form-label">Car Model</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year" required>
        </div>

        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price Per Day</label>
            <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" required>
        </div>

        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" class="form-control" id="license_plate" name="license_plate" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Car Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Car</button>
    </form>
</div>
@endsection
