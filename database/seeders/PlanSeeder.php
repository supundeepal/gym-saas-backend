<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Plan (0-100 Members)',
                'price' => 3500.00,
                'max_members' => 100,
                'features' => ['Core Gym Management', 'Basic Reports', 'Overdue Reminders']
            ],
            [
                'name' => 'Growth Plan (100-250 Members)',
                'price' => 6000.00,
                'max_members' => 250,
                'features' => ['Core Gym Management', 'Advanced Reports', 'Overdue Reminders', 'Attendance Alerts']
            ],
            [
                'name' => 'Pro Plan (250-500 Members)',
                'price' => 8500.00,
                'max_members' => 500,
                'features' => ['Everything in Growth', 'Staff Management', 'Priority Support']
            ],
            [
                'name' => 'Enterprise Plan (500+ Members)',
                'price' => 10000.00,
                'max_members' => 0, // 0 means Unlimited
                'features' => ['Unlimited Members', 'Custom Integrations', 'Dedicated Account Manager']
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create([
                'name' => $plan['name'],
                'slug' => Str::slug($plan['name']),
                'price' => $plan['price'],
                'billing_cycle' => 'monthly',
                'max_members' => $plan['max_members'],
                'max_staff' => 0,
                'features' => $plan['features'],
                'is_active' => true
            ]);
        }
    }
}