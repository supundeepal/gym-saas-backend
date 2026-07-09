<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    // ----- Web Views සඳහා (අලුතින් එකතු කළ කොටස) -----

    // වෙබ් පිටුව (Blade file එක) පෙන්වන function එක
   public function viewMemberships()
    {
        // පිටුව පමණක් පෙන්වන්න. ඩේටා ටික JavaScript (API) හරහා ගෙනත් දාගනීවි.
        return view('owner.memberships');
    }

    // ----- API සඳහා (ඔයාගේ කලින් තිබුණු කෝඩ් එක) -----

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
    }

    // අලුතින් පැකේජයක් (හෝ Day Fee එකක්) එකතු කිරීමේ function එක
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
    }// පැකේජයක් යාවත්කාලීන කිරීම (Edit)
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // අදාල ජිම් එකේ මේ පැකේජ් එක තියෙනවද කියලා බලනවා
        $package = Package::where('gym_id', $user->gym_id)->find($id);

        if (!$package) {
            return response()->json(['status' => 'error', 'message' => 'Package not found'], 404);
        }

        // එවන දත්ත පරීක්ෂා කිරීම
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration_in_days' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        // ඩේටාබේස් එක අප්ඩේට් කිරීම
        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_in_days' => $request->duration_in_days,
            'description' => $request->description,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Package updated successfully']);
    }

    // පැකේජයක් මකා දැමීම (Delete)
    public function destroy($id)
    {
        $user = Auth::user();
        
        $package = Package::where('gym_id', $user->gym_id)->find($id);

        if (!$package) {
            return response()->json(['status' => 'error', 'message' => 'Package not found'], 404);
        }

        // පැකේජය මකා දැමීම
        $package->delete();

        return response()->json(['status' => 'success', 'message' => 'Package deleted successfully']);
    }
}