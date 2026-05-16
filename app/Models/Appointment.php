<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id','doctor_id','appointment_date','appointment_time','status','reason','notes'];
    protected $casts    = ['appointment_date' => 'date'];

    const STATUS_PENDING   = 'pending';
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function patient() { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()  { return $this->belongsTo(Doctor::class); }
    public function review()  { return $this->hasOne(Review::class); }

    public function scopePending($q)   { return $q->where('status', 'pending'); }
    public function scopeAccepted($q)  { return $q->where('status', 'accepted'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
    public function scopeToday($q)     { return $q->whereDate('appointment_date', today()); }

    public function isPending():   bool { return $this->status === 'pending'; }
    public function isAccepted():  bool { return $this->status === 'accepted'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'En attente',
            'accepted'  => 'Accepté',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'accepted'  => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}
