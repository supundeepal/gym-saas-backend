<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    // ජිම් එකේ පැකේජ් ලැයිස්තුව ලබාදෙන function එක
    public function index()
    {
        $user = Auth::user();

        // ලොග් වෙලා ඉන්න කෙනාගේ ජිම් එකට (gym_id) අදාළ පැකේජ් පමණක් සොයාගැනීම
        $packages = Package::where('gym_id', $user->gym_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $packages
        ], 200);
    }// අලුතින් පැකේජයක් (හෝ Day Fee එකක්) එකතු කිරීමේ function එක
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. එවන දත්ත නිවැරදිදැයි පරීක්ෂා කිරීම
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration_in_days' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // 2. ජිම් ඇඩ්මින්ගේ ජිම් එකට අදාළව අලුත් පැකේජය Database එකට ලිවීම
        $package = Package::create([
            'gym_id' => $user->gym_id,
            'name' => $request->name,
            'price' => $request->price,
            'duration_in_days' => $request->duration_in_days,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Package added successfully',
            'data' => $package
        ], 201); // 201 යනු "සාර්ථකව නිර්මාණය කළා" යන්නයි
    }
}