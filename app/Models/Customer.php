<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nic',
        'name',
        'email',
        'address',
        'phone',
        'password',
    ];

    public function cars() {
        return $this->hasMany(Car::class);
    }
}
