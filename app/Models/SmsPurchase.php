<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsPurchase extends Model
{
    protected $fillable = ['gym_id', 'sms_package_id', 'slip_path', 'status'];

    // සම්බන්ධතා
    public function gym() {
        return $this->belongsTo(Gym::class);
    }
    public function package() {
        return $this->belongsTo(SmsPackage::class, 'sms_package_id');
    }
}