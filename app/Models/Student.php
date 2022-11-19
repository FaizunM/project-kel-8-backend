<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nis',
        'nisn',
        'fullname',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'religion',
        'address',
        'major_class_id',
        'major_id',
        'email',
        'phone_number',
    ];
}
