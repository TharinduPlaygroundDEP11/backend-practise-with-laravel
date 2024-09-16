<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarService extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'service_task_id',
        'status',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function task()
    {
        return $this->belongsTo(ServiceTask::class);
    }
}
