<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Staff;

use App\Models\Customer;



class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form for customers.
     *
     * @return \Illuminate\View\View
     */
    public function showCustomerRegisterForm()
    {
        return view('auth.custom-register', ['role' => 'customer']);
    }

    /**
     * Show the registration form for staff.
     *
     * @return \Illuminate\View\View
     */
    public function showStaffRegisterForm()
    {
        return view('auth.custom-register', ['role' => 'staff']);
    }

    /**
     * Validate and register a customer.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerCustomer(Request $request)
    {
        // Customer registration validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:500',
        ]);

        // Create the user for the customer
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer',
        ]);

        // Create customer-specific data
        $customer = new Customer();
        $customer->user_id = $user->id; // Link customer to user
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;
        $customer->save(); // Save customer data

        

        // Log the customer in
        Auth::login($user);

        // Redirect to customer dashboard
        return redirect('/customer/dashboard');
    }

    /**
     * Validate and register a staff member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerStaff(Request $request)
    {
        // Staff registration validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'branch' => ['required', 'in:Bandar Baru Bangi,Shah Alam,Gombak'],
        ]);

        // Create the user for staff
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'staff',
            'branch' => $request->branch, // Saving branch directly in the users table
        ]);

        // Create the staff entry
        Staff::create([
            'user_id' => $user->id,  // Linking to the user table
            'branch' => $request->branch, // Store branch in the staff table as well
        ]);

        // Log the staff in
        Auth::login($user);

        // Redirect to staff dashboard
        return redirect('/staff/dashboard');
    }
}
