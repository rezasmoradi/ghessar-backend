<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::query()->count()) User::query()->truncate();

        $this->createAdminUser();
        $this->createInspectorUser();
        $this->createUser();

        $this->command->info('مدیر، بازرس و کاربر عادی ایجاد شدند.');
    }

    private function createAdminUser()
    {
        User::query()->create([
            'type' => User::USER_TYPE_ADMIN,
            'first_name' => 'Reza',
            'last_name' => 'Seidmoradi',
            'username' => 'Reza_Seidmoradi',
            'password' => '12345678',
            'mobile' => '09199199199',
            'email' => 'reza.seidmoradi@outlook.com',
            'verified_at' => now()->subMonths(),
            'bio' => 'کلمات قصار',
            'country' => 'iran'
        ]);
    }

    private function createInspectorUser()
    {
        User::query()->create([
            'type' => User::USER_TYPE_INSPECTOR,
            'first_name' => 'Ali',
            'last_name' => 'Karimi',
            'username' => 'Ali_Karimi',
            'password' => '87654321',
            'mobile' => '09188188188',
            'email' => 'ali.karimi@gmail.com',
            'verified_at' => now()->subDays(10),
            'bio' => 'بازرس قصار',
            'country' => 'iran'
        ]);
    }

    private function createUser()
    {
        User::query()->create([
            'type' => User::USER_TYPE_USER,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'John_Doe',
            'password' => '12345678',
            'mobile' => '09122121212',
            'email' => 'john.doe@yahoo.com',
            'verified_at' => now()->subDays(7),
            'bio' => 'که با این در اگر در بند در ماند، درمانند!',
            'country' => 'france',
        ]);
    }
}
