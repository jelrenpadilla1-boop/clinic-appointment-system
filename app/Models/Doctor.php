<?php
// app/Models/Doctor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'specialization_id', 
        'license_number',
        'qualification',
        'experience',
        'bio',
        'fee',
        'max_patients',
        'services'
    ];

    protected $casts = [
        'services' => 'array',
        'fee' => 'decimal:2',
        'experience' => 'integer',
        'max_patients' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get services as array
     */
    public function getServicesListAttribute()
    {
        return $this->services ?? [];
    }

    /**
     * Check if doctor offers a specific service
     */
    public function offersService($service)
    {
        return in_array($service, $this->services_list);
    }
}