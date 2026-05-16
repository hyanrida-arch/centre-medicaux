<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','specialization','consultation_fee','biography','is_available'];
    protected $casts    = ['is_available' => 'boolean', 'consultation_fee' => 'decimal:2'];

    public function user()        { return $this->belongsTo(User::class); }
    public function appointments(){ return $this->hasMany(Appointment::class); }
    public function reviews()     { return $this->hasMany(Review::class); }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->whereHas('user', function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name',  'like', "%{$term}%");
        })->orWhere('specialization', 'like', "%{$term}%");
    }

    public function scopeByFee($query, float $min, float $max)
    {
        return $query->whereBetween('consultation_fee', [$min, $max]);
    }
}
