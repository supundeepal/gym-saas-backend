<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MGFitnessSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin නිර්මාණය කිරීම (මේක ඔයාගේ ගිණුම)
        $superAdminId = DB::table('users')->insertGetId([
            'name' => 'System Owner',
            'email' => 'superadmin@app.com', // ලොග් වෙන්න පාවිච්චි කරන ඊමේල් එක
            'password' => Hash::make('password'), // පාස්වර්ඩ් එක විදිහට 'password' යොදා ඇත
            'role' => 'super_admin',
            'gym_id' => null, // Super Admin ට ජිම් එකක් නැත (ඔහු සියල්ලටම ඉහළින් සිටී)
            'phone' => '0700000000',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. ජිම් එක Database එකට ඇතුළත් කිරීම
        $gymId = DB::table('gyms')->insertGetId([
            'name' => 'MG Fitness Center',
            'slug' => 'mg-fitness-center',
            'phone_number' => '076 1265 965',
            'address' => 'Main Street, Pallegama Road, Deniyaya',
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3. ජිම් එකේ අයිතිකරු (Gym Admin) නිර්මාණය කිරීම
        DB::table('users')->insert([
            'name' => 'MG Fitness Admin',
            'email' => 'admin@mgfitness.com', // Gym Admin ලොග් වෙන ඊමේල් එක
            'password' => Hash::make('password'), // පාස්වර්ඩ් එක විදිහට 'password' යොදා ඇත
            'role' => 'gym_admin',
            'gym_id' => $gymId, // මේ Admin අයිති වෙන්නේ MG Fitness ජිම් එකටයි
            'phone' => '0761265965',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 4. ජිම් එකේ පැකේජ් ලැයිස්තුව
        $packages = [
            // One Person Packages
            ['name' => 'One Person - 1 Month', 'price' => 3000.00, 'duration_in_days' => 30, 'description' => 'Solo training package for 1 month'],
            ['name' => 'One Person - 3 Months', 'price' => 7000.00, 'duration_in_days' => 90, 'description' => 'Solo training package for 3 months'],
            ['name' => 'One Person - 6 Months', 'price' => 16000.00, 'duration_in_days' => 180, 'description' => 'Solo training package for 6 months'],
            ['name' => 'One Person - Annual', 'price' => 30000.00, 'duration_in_days' => 365, 'description' => 'Solo training package for 1 year'],

            // Couple Packages
            ['name' => 'Couple Package - 1 Month', 'price' => 5000.00, 'duration_in_days' => 30, 'description' => 'Train better together! Couple package for 1 month'],
            ['name' => 'Couple Package - 3 Months', 'price' => 13000.00, 'duration_in_days' => 90, 'description' => 'Train better together! Couple package for 3 months'],
            ['name' => 'Couple Package - 6 Months', 'price' => 28000.00, 'duration_in_days' => 180, 'description' => 'Train better together! Couple package for 6 months'],
            ['name' => 'Couple Package - Annual', 'price' => 50000.00, 'duration_in_days' => 365, 'description' => 'Train better together! Couple package for 1 year'],

            // Ladies & Kids Packages
            ['name' => 'Ladies & Kids - 1 Month', 'price' => 2500.00, 'duration_in_days' => 30, 'description' => 'Safe, supportive environment for ladies and youth (Under 18)'],
            ['name' => 'Ladies & Kids - 3 Months', 'price' => 6500.00, 'duration_in_days' => 90, 'description' => 'Safe, supportive environment for ladies and youth (Under 18)'],
            ['name' => 'Ladies & Kids - 6 Months', 'price' => 12000.00, 'duration_in_days' => 180, 'description' => 'Safe, supportive environment for ladies and youth (Under 18)'],
            ['name' => 'Ladies & Kids - Annual', 'price' => 24000.00, 'duration_in_days' => 365, 'description' => 'Safe, supportive environment for ladies and youth (Under 18)'],
        ];

        // 5. පැකේජ් ටික Database එකේ packages table එකට යැවීම
        foreach ($packages as $package) {
            DB::table('packages')->insert(array_merge($package, [
                'gym_id' => $gymId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}