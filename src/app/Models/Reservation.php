<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'time',
        'number',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function myPage()
    {
        $reservations = Reservation::with('shop')
            ->where('user_id', auth()->id())
            ->get();

        return view('myPage', compact('reservations'));
    }

}
