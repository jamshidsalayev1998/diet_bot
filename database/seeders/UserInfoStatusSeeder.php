<?php

namespace Database\Seeders;

use App\Models\V1\UserInfoStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserInfoStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = config('projectDefaultValues.user_info_statuses');
        foreach($statuses as $status){
            if(!UserInfoStatus::where('title' , $status)->exists()) UserInfoStatus::create(['title' => $status]);
        }
    }
}
