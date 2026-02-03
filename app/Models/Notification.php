<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'type',
        'title',
        'message',
        'read_at',
    ];
    protected $casts = [
        'read_at' => 'datetime',
    ];
}
