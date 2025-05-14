@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">
        {{ ucfirst($role) }} Registration
    </h2>

    <form method="POST" action="{{ $role === 'staff' ? route('register.staff') : route('register.customer') }}">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Register as {{ ucfirst($role) }}
        </button>
    </form>
</div>
@endsection
