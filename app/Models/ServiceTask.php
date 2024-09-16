<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_section_id',
    ];

    public function section()
    {
        return $this->belongsTo(ServiceSection::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_services')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
