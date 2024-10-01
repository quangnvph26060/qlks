<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_id', 'publish'];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    protected $casts = [
        'publish' => 'boolean'
    ];
}
