<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarService;
use App\Models\Customer;
use App\Models\ServiceTask;
use Illuminate\Http\Request;

class CarServiceController extends Controller
{
    public function searchCustomer(Request $request)
    {
        $query = $request->input('query');
        $customers = Customer::where('nic', 'LIKE', "%$query%")
            ->orWhere('email', 'LIKE', "%$query%")
            ->orWhere('name', 'LIKE', "%$query%")
            ->with('cars')
            ->paginate(10);

        return response()->json($customers);
    }

    public function initiateServices($carId)
    {
        $car = Car::with('services')->findOrFail($carId);
        $services = ServiceTask::all();

        return response()->json([
            'car' => $car,
            'services' => $services
        ]);
    }

    public function storeServices(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'service_tasks' => 'required|array',
        ]);

        $carId = $data['car_id'];
        $serviceTasks = $data['service_tasks'];

        foreach ($serviceTasks as $taskId) {
            CarService::updateOrCreate(
                ['car_id' => $carId, 'service_task_id' => $taskId],
                ['status' => 'Pending']
            );
        }

        return response()->json(['message' => 'Services initiated successfully.']);
    }

    public function currentJobs()
    {
        $jobs = CarService::with('car.customer', 'task')
            ->whereDate('created_at', now()->toDateString())
            ->get()
            ->groupBy('car_id');

        foreach ($jobs as $carId => $services) {
            $completedTasks = $services->filter(function ($job) {
                return $job->status === 'Completed';
            })->count();

            $totalTasks = $services->count();
            $percentage = $totalTasks ? ($completedTasks / $totalTasks) * 100 : 0;

            $jobs[$carId]->progress = $percentage;
        }

        return response()->json($jobs);
    }

    public function updateJobStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed'
        ]);

        $carService = CarService::findOrFail($id);
        $carService->status = $request->input('status');
        $carService->save();

        // Check if all services for the car are completed
        $car = Car::find($carService->car_id);
        $allServicesCompleted = $car->services()->wherePivot('status', '!=', 'Completed')->count() === 0;

        if ($allServicesCompleted) {
            // Send email to the customer
            // TODO: Implement email sending logic
        }

        return response()->json(['message' => 'Job status updated successfully.']);
    }

}
