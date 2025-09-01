<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::factory([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => bcrypt('coachtech')
        ])->has(Profile::factory([
            'profile_image' => 'profile_sample/user1.png'
        ]))->create();

        $user2 = User::factory([
            'name' => 'user2',
            'email' => 'user2@example.com',
            'password' => bcrypt('coachtech')
        ])->has(Profile::factory([
            'profile_image' => 'profile_sample/user2.png'
        ]))->create();


        $products = [
            [
                'user_id' => $user1->id,
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'categories' => [1, 5],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Armani+Mens+Clock.jpg'))
                ),
                'condition' => '良好',
            ],
            [
                'user_id' => $user1->id,
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'categories' => [2, 8],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/HDD+Hard+Disk.jpg'))
                ),
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => $user2->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'categories' => [10],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/iLoveIMG+d.jpg'))
                ),
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => $user2->id,
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'categories' => [1, 5],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Leather+Shoes+Product+Photo.jpg'))
                ),
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => $user1->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'categories' => [2],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Living+Room+Laptop.jpg'))
                ),
                'condition' => '良好',
            ],
            [
                'user_id' => $user2->id,
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'categories' => [2],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Music+Mic+4632231.jpg'))
                ),
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => $user2->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'categories' => [1, 4],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Purse+fashion+pocket.jpg'))
                ),
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => $user1->id,
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'categories' => [10],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Tumbler+souvenir.jpg'))
                ),
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => $user2->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'categories' => [2, 10],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/Waitress+with+Coffee+Grinder.jpg'))
                ),
                'condition' => '良好',
            ],
            [
                'user_id' => $user1->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'categories' => [1, 4, 6],
                'product_image' => Storage::disk('public')->putFile(
                    'product_images',
                    new File(database_path('seeders/images/外出メイクアップセット.jpg'))
                ),
                'condition' => '目立った傷や汚れなし',
            ],
        ];
        foreach ($products as $data) {
            $categories = $data['categories'];
            unset($data['categories']);

            $product = Product::factory()->create($data);
            $product->categories()->attach($categories);
        }
    }
}
