<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $customerId = $request->query('customer_id');

        $query = Car::query();

        if ($search) {
            $query->where('registration_number', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%');
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $cars = $query->paginate(10);

        return response()->json($cars, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_number' => 'required|unique:cars|max:255',
            'model' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id'
        ], [
            'registration_number.required' => 'The registration number field is mandatory.',
            'registration_number.unique' => 'This registration number is already in use.',
            'model.required' => 'The model field is mandatory.',
            'fuel_type.required' => 'The fuel type field is mandatory.',
            'transmission.required' => 'The transmission field is mandatory.',
            'customer_id.exists' => 'Invalid customer.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $car = Car::create([
                'registration_number' => $request->input('registration_number'),
                'model' => $request->input('model'),
                'fuel_type' => $request->input('fuel_type'),
                'transmission' => $request->input('transmission'),
                'customer_id' => $request->input('customer_id'),
            ]);

            return response()->json(['message' => 'Car created', 'data' => $car], 201);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json($car, 200);
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'registration_number' => 'sometimes|required|unique:cars,registration_number,' . $id . '|max:255',
            'model' => 'sometimes|required|string|max:255',
            'fuel_type' => 'sometimes|required|string|max:255',
            'transmission' => 'sometimes|required|string|max:255',
            'customer_id' => 'sometimes|required|exists:customers,id',
        ], [
            'registration_number.unique' => 'This registration number is already in use.',
            'customer_id.exists' => 'Invalid customer.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $car->update($request->only(['registration_number', 'model', 'fuel_type', 'transmission', 'customer_id']));

            return response()->json(['message' => 'Car updated', 'data' => $car], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        try {
            $car->delete();
            return response()->json(['message' => 'Car deleted'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
