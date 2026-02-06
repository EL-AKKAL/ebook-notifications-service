<?php

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
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
        'type' => NotificationTypeEnum::class,
    ];

    protected $hidden = ['type'];

    protected $appends = ['is_read', 'type_object'];

    protected function isRead(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->read_at !== null
        );
    }

    protected function typeObject(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type
            ? [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ]
            : null
        );
    }

    public function scopeForUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function markAsRead(): bool
    {
        return $this->updateQuietly(['read_at' => now()]);
    }

    public function markAsUnread(): bool
    {
        return $this->updateQuietly(['read_at' => null]);
    }
}
