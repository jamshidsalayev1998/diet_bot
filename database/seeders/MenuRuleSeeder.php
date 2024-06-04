<?php

namespace Database\Seeders;

use App\Models\V1\MenuRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('projectDefaultValues.menu_rules');
        foreach ($permissions as $permission) {
            if (!MenuRule::where('title' , json_encode($permission))->exists()) MenuRule::create(['title' => json_encode($permission)]);
        }
    }
}
