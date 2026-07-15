<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    // මෙතන plan_id එක අලුතින් එකතු කළා
    protected $fillable = ['name', 'slug', 'plan_id', 'sms_balance', 'address', 'phone', 'logo_path'];

    // ජිම් එකට අයිති යූසර්ස්ලා ගන්න Function එක
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // 🔴 මේක තමයි මම අලුතින් දාන්න කිව්ව Relationship Function එක 🔴
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}