<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;  
class CarController extends Controller
{
    public function index()
{
    // Example: Fetch all cars from database and pass to view
    $cars = \App\Models\Car::all(); // Adjust model namespace if needed

    return view('cars.index', compact('cars'));
}

}
