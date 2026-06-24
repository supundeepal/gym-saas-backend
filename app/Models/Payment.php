<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Database එකට දත්ත ඇතුළත් කිරීමට අවසර ලබා දීම
    protected $guarded = [];
}