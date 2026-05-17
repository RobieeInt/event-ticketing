<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'banner_image',
        'category',
        'event_date',
        'location',
        'ticket_price',
        'ticket_capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date'   => 'datetime',
            'ticket_price' => 'decimal:2',
        ];
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function ticketsSold(): int
    {
        return $this->orders()->where('status', 'paid')->sum('quantity');
    }

    public function remainingCapacity(): int
    {
        return $this->ticket_capacity - $this->ticketsSold();
    }

    public function isFree(): bool
    {
        return $this->ticket_price == 0;
    }

    public static function categories(): array
    {
        return [
            'music'      => 'Musik',
            'sports'     => 'Olahraga',
            'seminar'    => 'Seminar',
            'exhibition' => 'Pameran',
            'conference' => 'Konferensi',
            'other'      => 'Lainnya',
        ];
    }
}
