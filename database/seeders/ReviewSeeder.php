<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\UserProduct;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ========================================================================
        // BANK KATA UNTUK ULASAN YANG LEBIH RANDOM
        // ========================================================================

        // Untuk rating 5 ★★★★★ (Sangat Positif)
        $adjectives5 = ['Luar biasa', 'Keren banget', 'Fantastis', 'Menakjubkan', 'Indah', 'Spektakuler', 'Mengagumkan'];
        $nouns = ['Ceritanya', 'Gambarnya', 'Alur ceritanya', 'Karakternya', 'Kualitas gambarnya', 'Visualnya', 'Pewarnaannya'];
        $closers5 = [
            'Sangat direkomendasikan!',
            'Wajib dibaca pokoknya!',
            'Nggak sabar nunggu lanjutannya.',
            'Pasti beli episode berikutnya.',
            'Karya anak bangsa yang membanggakan!',
            'Salah satu yang terbaik!'
        ];

        // Untuk rating 4 ★★★★☆ (Positif dengan sedikit catatan)
        $adjectives4 = ['Bagus', 'Oke', 'Cukup menarik', 'Jelas', 'Rapi', 'Menarik'];
        $critiques = [
            'meskipun ada sedikit typo.',
            'tapi beberapa panel gambarnya agak gelap.',
            'walaupun ceritanya agak ketebak.',
            'tapi saya berharap episodenya lebih panjang.',
            'sayang sekali endingnya agak gantung.'
        ];
        $closers4 = [
            'Secara keseluruhan bagus.',
            'Layak untuk dibaca.',
            'Cukup memuaskan.',
            'Boleh juga.'
        ];

        // ========================================================================

        $productSlugs = [
            'pengenalan-karakter-nataga-the-little-dragon',
            'episode-1-nataga-the-little-dragon',
            'episode-2-nataga-the-little-dragon',
            'episode-3-nataga-the-little-dragon',
            'perspective-eric-f-scott',
        ];

        foreach ($productSlugs as $productSlug) {
            $product = Product::where('slug', $productSlug)->first();
            if (!$product) {
                $this->command->warn("⚠️ Product with slug '{$productSlug}' for review not found. Skipping.");
                continue;
            }

            // Tentukan rating rata-rata berdasarkan slug
            $avgRating = ($productSlug == 'perspective-eric-f-scott') ? 4.5 : 5.0;

            $userProducts = UserProduct::where('product_id', $product->id)
                ->whereDoesntHave('orderItem.review')
                ->get();

            if ($userProducts->isEmpty()) {
                $this->command->info("ℹ️ No new buyers to review for: {$product->name}");
                continue;
            }

            $reviewerCount = max(1, intval($userProducts->count() * $faker->randomFloat(2, 0.4, 0.7)));
            $reviewers = $userProducts->random(min($reviewerCount, $userProducts->count()));

            foreach ($reviewers as $userProduct) {
                $rating = ($avgRating == 4.5) ? $faker->randomElement([4, 5]) : 5;
                $comment = null;

                // 85% kemungkinan user memberikan komentar, sisanya hanya rating
                if ($faker->boolean(85)) {
                    if ($rating === 5) {
                        // Pilih format kalimat acak untuk rating 5
                        $format = rand(1, 3);
                        switch ($format) {
                            case 1:
                                $comment = $faker->randomElement($adjectives5) . ' banget ' . lcfirst($faker->randomElement($nouns)) . 'nya! ' . $faker->randomElement($closers5);
                                break;
                            case 2:
                                $comment = $faker->randomElement($closers5) . ' ' . $faker->randomElement($nouns) . 'nya ' . lcfirst($faker->randomElement($adjectives5)) . '.';
                                break;
                            default:
                                $comment = $faker->randomElement($closers5);
                                break;
                        }
                    } else { // Rating 4
                        // Pilih format kalimat acak untuk rating 4
                        $format = rand(1, 2);
                        switch ($format) {
                            case 1:
                                $comment = $faker->randomElement($adjectives4) . ', ' . $faker->randomElement($critiques);
                                break;
                            default:
                                $comment = $faker->randomElement($closers4) . ' ' . $faker->randomElement($nouns) . 'nya ' . lcfirst($faker->randomElement($adjectives4)) . '.';
                                break;
                        }
                    }
                }

                $reviewDate = Carbon::parse($userProduct->created_at)->addDays(rand(1, 15));
                if ($reviewDate->isFuture()) {
                    $reviewDate = Carbon::now()->subMinutes(rand(1, 500));
                }

                Review::create([
                    'user_id'       => $userProduct->user_id,
                    'product_id'    => $product->id,
                    'order_item_id' => $userProduct->order_item_id,
                    'rating'        => $rating,
                    'comment'       => $comment, // Bisa jadi null
                    'created_at'    => $reviewDate,
                    'updated_at'    => $reviewDate,
                ]);
            }
            $this->command->info("✅ Generated " . $reviewers->count() . " reviews for: {$product->name}");
        }
    }
}
