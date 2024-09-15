<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('email');

        if ($search) {
            $customers = Customer::where('email', 'like', '%' . $search . '%')->paginate(10);
        } else {
            $customers = Customer::paginate(10);
        }

        return response()->json($customers, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nic' => 'required|unique:customers|max:20',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ], [
            'nic.required' => 'The NIC field is mandatory.',
            'nic.unique' => 'This NIC is already in use.',
            'name.required' => 'The name field is mandatory.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already in use.',
            'address.required' => 'The address field is mandatory.',
            'phone.required' => 'The phone field is mandatory.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $password = Hash::make('customer123');
        
        try {
            $customer = Customer::create([
                'nic' => $request->input('nic'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'password' => $password
            ]);

            return response()->json(['message' => 'Customer created', 'data' => $customer], 201);

        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nic' => 'sometimes|required|max:20|unique:customers,nic,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:customers,email,' . $id,
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'password' => 'nullable|string|min:8',
        ], [
            'nic.required' => 'The NIC field is mandatory.',
            'nic.unique' => 'This NIC is already in use.',
            'name.required' => 'The name field is mandatory.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already in use.',
            'address.required' => 'The address field is mandatory.',
            'phone.required' => 'The phone field is mandatory.',
            'password.min' => 'The password must be at least 8 characters long.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->has('nic')) $customer->nic = $request->input('nic');
            if ($request->has('name')) $customer->name = $request->input('name');
            if ($request->has('email')) $customer->email = $request->input('email');
            if ($request->has('address')) $customer->address = $request->input('address');
            if ($request->has('phone')) $customer->phone = $request->input('phone');
            if ($request->has('password') && !empty($request->input('password'))) $customer->password = Hash::make($request->input('password'));

            $customer->save();

            return response()->json(['message' => 'Customer updated', 'data' => $customer], 200);

        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer, 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        try {
            $customer->delete();
            return response()->json(['message' => 'Customer deleted'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
