<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Matematika', 'level' => 'all'],
            ['name' => 'Bahasa Indonesia', 'level' => 'all'],
            ['name' => 'Bahasa Inggris', 'level' => 'all'],
            ['name' => 'IPA', 'level' => 'smp'],
            ['name' => 'IPS', 'level' => 'smp'],
            ['name' => 'Fisika', 'level' => 'sma'],
            ['name' => 'Biologi', 'level' => 'sma'],
            ['name' => 'Kimia', 'level' => 'sma'],
            ['name' => 'Ekonomi', 'level' => 'sma'],
            ['name' => 'Pendidikan Agama', 'level' => 'all'],
            ['name' => 'Pendidikan Pancasila', 'level' => 'all'],
            ['name' => 'Seni Budaya', 'level' => 'all'],
            ['name' => 'PJOK', 'level' => 'all'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
