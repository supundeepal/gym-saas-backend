<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsPackage; // ඩේටාබේස් මොඩල් එක

class SmsPackageController extends Controller
{
    // 1. සිස්ටම් එකේ තියෙන ඔක්කොම SMS Packages ටික ගන්න (Super Admin ට වගේම Gym Owner ටත් මේක ඕන වෙනවා)
    public function index()
    {
        $packages = SmsPackage::orderBy('price', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $packages
        ]);
    }

    // 2. අලුතින් SMS Package එකක් හදන්න (මේක කරන්නේ Super Admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // උදා: "Bronze Pack"
            'sms_count' => 'required|integer',   // උදා: 1000
            'price' => 'required|numeric'        // උදා: 500.00
        ]);

        $package = SmsPackage::create([
            'name' => $request->name,
            'sms_count' => $request->sms_count,
            'price' => $request->price,
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'SMS Package created successfully!',
            'data' => $package
        ]);
    }

    // 3. හදපු පැකේජ් එකක් මකන්න (Super Admin)
    public function destroy($id)
    {
        $package = SmsPackage::find($id);
        if ($package) {
            $package->delete();
            return response()->json(['status' => 'success', 'message' => 'Package deleted successfully.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Package not found.'], 404);
    }
}