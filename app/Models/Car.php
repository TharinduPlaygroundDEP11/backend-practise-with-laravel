<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'model',
        'fuel_type',
        'transmission',
        'customer_id',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function services()
    {
        return $this->belongsToMany(ServiceTask::class, 'car_services')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
