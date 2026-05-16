<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['first_name','last_name','email','password','role','photo','phone'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/photos/' . $this->photo)
            : asset('images/default-avatar.png');
    }

    public function isDoctor(): bool  { return $this->role === 'doctor'; }
    public function isPatient(): bool { return $this->role === 'patient'; }

    public function doctor()               { return $this->hasOne(Doctor::class); }
    public function appointmentsAsPatient(){ return $this->hasMany(Appointment::class, 'patient_id'); }
    public function sentMessages()         { return $this->hasMany(Message::class, 'sender_id'); }
    public function receivedMessages()     { return $this->hasMany(Message::class, 'receiver_id'); }
    public function unreadMessages()       { return $this->hasMany(Message::class, 'receiver_id')->where('is_read', false); }
}
