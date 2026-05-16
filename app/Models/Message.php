<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id','receiver_id','body','is_read'];
    protected $casts    = ['is_read' => 'boolean'];

    public function sender()   { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }

    public static function conversationsFor(User $user)
    {
        $sent     = static::where('sender_id', $user->id)->pluck('receiver_id');
        $received = static::where('receiver_id', $user->id)->pluck('sender_id');
        return User::whereIn('id', $sent->merge($received)->unique())->get();
    }
}
