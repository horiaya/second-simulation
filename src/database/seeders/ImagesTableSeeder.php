<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // 保存した画像ファイルのパスをデータベースに追加
        Image::create([
            'file_path' => 'images/sushi.jpg',
        ]);
        // storage/app/public/images/sushi.jpgに対応
        Image::create([
            'file_path' => 'images/yakiniku.jpg',
        ]);
        Image::create([
            'file_path' => 'images/izakaya.jpg',
        ]);
        Image::create([
            'file_path' => 'images/ramen.jpg',
        ]);
        Image::create([
            'file_path' => 'images/italian.jpg',
        ]);
    }
}
