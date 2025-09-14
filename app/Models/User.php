<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'google_id',
        'profile_photo_path',
        'bio',
        'date_of_birth',
        'gender',
        'phone',
        'height',
        'weight',
        'activity_level',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return $this->name;
    }

    /**
     * Get the user's age.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Calculate and get BMI.
     */
    public function getBmiAttribute(): ?float
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 1);
        }
        return null;
    }

    /**
     * Get BMI category.
     */
    public function getBmiCategoryAttribute(): ?string
    {
        $bmi = $this->bmi;

        if (!$bmi) {
            return null;
        }

        if ($bmi < 18.5) {
            return 'Underweight';
        } elseif ($bmi < 25) {
            return 'Normal weight';
        } elseif ($bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obese';
        }
    }

    /**
     * Get the ideal weight range based on height.
     */
    public function getIdealWeightRangeAttribute(): ?array
    {
        if (!$this->height) {
            return null;
        }

        $heightInMeters = $this->height / 100;
        $minWeight = round(18.5 * ($heightInMeters * $heightInMeters), 1);
        $maxWeight = round(24.9 * ($heightInMeters * $heightInMeters), 1);

        return [
            'min' => $minWeight,
            'max' => $maxWeight
        ];
    }

    /**
     * Calculate BMR (Basal Metabolic Rate) using Harris-Benedict equation.
     */
    public function getBmrAttribute(): ?int
    {
        if (!$this->weight || !$this->height || !$this->age || !$this->gender) {
            return null;
        }

        if ($this->gender === 'male') {
            $bmr = 88.362 + (13.397 * $this->weight) + (4.799 * $this->height) - (5.677 * $this->age);
        } else {
            $bmr = 447.593 + (9.247 * $this->weight) + (3.098 * $this->height) - (4.330 * $this->age);
        }

        return round($bmr);
    }

    /**
     * Calculate daily calorie needs based on activity level.
     */
    public function getDailyCaloriesAttribute(): ?int
    {
        $bmr = $this->bmr;

        if (!$bmr || !$this->activity_level) {
            return null;
        }

        $multipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'very_active' => 1.725,
            'extremely_active' => 1.9
        ];

        return round($bmr * ($multipliers[$this->activity_level] ?? 1.2));
    }

    /**
     * Check if user has complete profile.
     */
    public function hasCompleteProfile(): bool
    {
        return !empty($this->first_name) &&
            !empty($this->last_name) &&
            !empty($this->date_of_birth) &&
            !empty($this->gender) &&
            !empty($this->height) &&
            !empty($this->weight);
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'first_name',
            'last_name',
            'username',
            'email',
            'date_of_birth',
            'gender',
            'phone',
            'height',
            'weight',
            'activity_level',
            'profile_photo_path',
            'bio'
        ];

        $completedFields = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completedFields++;
            }
        }

        return round(($completedFields / count($fields)) * 100);
    }

    /**
     * Scope untuk mencari user berdasarkan nama atau username.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('username', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
    }
}