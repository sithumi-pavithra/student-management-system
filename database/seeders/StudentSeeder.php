<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                'name' => 'Sithumi Pavithra',
                'email' => 'sithumi@test.com',
                'phone' => '0712345678',
                'course' => 'HNDIT',
                'address' => 'Colombo',
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'phone' => '0723456789',
                'course' => 'HNDA',
                'address' => 'Kandy',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@test.com',
                'phone' => '0734567890',
                'course' => 'HNDB',
                'address' => 'Galle',
            ],
            [
                'name' => 'Mike Brown',
                'email' => 'mike@test.com',
                'phone' => '0745678901',
                'course' => 'HNDIT',
                'address' => 'Matara',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@test.com',
                'phone' => '0756789012',
                'course' => 'HNDE',
                'address' => 'Negombo',
            ],
            [
                'name' => 'James Anderson',
                'email' => 'james@test.com',
                'phone' => '0767890123',
                'course' => 'HNDIT',
                'address' => 'Kurunegala',
            ],
            [
                'name' => 'Olivia Taylor',
                'email' => 'olivia@test.com',
                'phone' => '0778901234',
                'course' => 'HNDA',
                'address' => 'Anuradhapura',
            ],
            [
                'name' => 'William Martinez',
                'email' => 'william@test.com',
                'phone' => '0789012345',
                'course' => 'HNDB',
                'address' => 'Jaffna',
            ],
            [
                'name' => 'Sophia Garcia',
                'email' => 'sophia@test.com',
                'phone' => '0790123456',
                'course' => 'HNDIT',
                'address' => 'Batticaloa',
            ],
            [
                'name' => 'David Rodriguez',
                'email' => 'david@test.com',
                'phone' => '0701234567',
                'course' => 'HNDE',
                'address' => 'Trincomalee',
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('✅ 10 students added successfully!');
    }
}