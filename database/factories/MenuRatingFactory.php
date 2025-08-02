<?php

namespace Database\Factories;

use App\Models\MenuRating;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuRatingFactory extends Factory
{
    protected $model = MenuRating::class;

    public function definition()
    {
        // สุ่มค่าระหว่าง 1 ถึง 5
        $rating = $this->faker->numberBetween(1, 5);
        // สุ่ม comment ตาม rating
        $comment = match ($rating) {
            1 => 'Not good',
            2 => 'Could be better',
            3 => 'Average',
            4 => 'Good',
            5 => 'Excellent',
            default => 'No comment',
        };

        return [
            'menu_id' => $this->faker->numberBetween(1, 45), // menu_id สุ่มตั้งแต่ 1-45
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
