<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsPackage; 
use App\Models\SmsPurchase;

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
    }// 4. Super Admin ට ආපු ඔක්කොම Slip Uploads ටික පෙන්වීම
    public function getPurchases()
    {
        // Gym එකේ විස්තරයි, පැකේජ් එකේ විස්තරයි එක්කම ගන්නවා
        $purchases = SmsPurchase::with(['gym', 'package'])->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $purchases]);
    }

    // 5. Super Admin Slip එක බලලා Approve කිරීම
    public function approvePurchase($id)
    {
        $purchase = SmsPurchase::with(['gym', 'package'])->find($id);
        
        if ($purchase && $purchase->status === 'pending') {
            $purchase->status = 'approved';
            $purchase->save();

            // Gym එකේ SMS Balance එක වැඩි කරනවා
            $gym = $purchase->gym;
            $gym->sms_balance += $purchase->package->sms_count;
            $gym->save();

            // (Gym Owner ට Email/SMS යන කෑල්ල අපි ඊළඟට මෙතනට දාමු)

            return response()->json(['status' => 'success', 'message' => 'Package activated successfully!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid request.'], 400);
    }

    // 6. Slip එක බොරු එකක් නම් Reject කිරීම
    public function rejectPurchase($id)
    {
        $purchase = SmsPurchase::find($id);
        if ($purchase && $purchase->status === 'pending') {
            $purchase->status = 'rejected';
            $purchase->save();
            return response()->json(['status' => 'success', 'message' => 'Request rejected.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid request.'], 400);
    }
}