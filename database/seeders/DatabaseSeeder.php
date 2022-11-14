<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'role' => 'admin',
            'username' => 'admin',
            'email' => 'admin@smk.com',
            'password' => '37920320'
        ]);

        Student::factory(272)->create();
        Teacher::factory(72)->create();
    }
}
