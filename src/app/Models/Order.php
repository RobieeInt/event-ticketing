<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'total_price',
        'status',
        'order_code',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price'    => 'decimal:2',
            'checked_in_at'  => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public static function generateCode(): string
    {
        return 'ORD-' . strtoupper(date('Ymd')) . '-' . strtoupper(substr(uniqid(), -6));
    }
}
