<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    use HasFactory;

    // 🔴 මෙන්න මේ පේළිය තමයි අඩුවෙලා තිබ්බේ 🔴
    protected $fillable = ['gym_id', 'plan_id', 'billing_cycle', 'slip_path', 'status'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}