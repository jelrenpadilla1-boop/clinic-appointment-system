<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '1234567890',
            'address' => 'Admin Address',
        ]);

        // Create Specializations
        $specializations = [
            ['name' => 'Cardiology', 'description' => 'Heart specialists'],
            ['name' => 'Dermatology', 'description' => 'Skin specialists'],
            ['name' => 'Pediatrics', 'description' => 'Child specialists'],
            ['name' => 'Orthopedics', 'description' => 'Bone specialists'],
            ['name' => 'Neurology', 'description' => 'Brain specialists'],
        ];

        foreach ($specializations as $spec) {
            Specialization::create($spec);
        }

        // Create Doctors
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Doctor $i",
                'email' => "doctor$i@clinic.com",
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => "987654321$i",
                'address' => "Doctor $i Address",
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $i,
                'license_number' => "LIC$i" . rand(1000, 9999),
            ]);

            // Create schedules for each doctor
            $days = [1, 2, 3, 4, 5]; // Monday to Friday
            foreach ($days as $day) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'max_patients' => 10,
                ]);
            }
        }

        // Create Patients
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Patient $i",
                'email' => "patient$i@clinic.com",
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => "55512345$i",
                'address' => "Patient $i Address",
            ]);
        }
    }
}