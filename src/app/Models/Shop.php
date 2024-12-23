<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['shop_name', 'image', 'text', 'genre_id', 'area_id', 'address', 'tell','email', 'regular_holidays','closed_days','holidays_message', 'open_hours', 'close_hours', 'representative_name', 'owner_id'];

    //空の配列としてエラーを回避
    protected $casts = [
    'regular_holidays' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }

    //エリアでの絞り込み
    public function scopeArea($query, $areaId)
    {
        if (!empty($areaId)) {
            return $query->where('area_id', $areaId);
        }
        return $query;
    }

    // ジャンルでの絞り込み
    public function scopeGenre($query, $genreId)
    {
        if (!empty($genreId)) {
            return $query->where('genre_id', $genreId);
        }
        return $query;
    }
    // キーワード検索（店名や説明文を検索対象とする例）
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('shop_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('text', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
