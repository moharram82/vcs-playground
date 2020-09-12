<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'body' => $this->faker->sentences(10, true),
        ];
    }

    public function published()
    {
        return $this->state(
            function () {
                return [
                    'is_published' => true,
                ];
            }
        );
    }

    public function unpublished()
    {
        return $this->state(
            function () {
                return [
                    'is_published' => false,
                ];
            }
        );
    }
}
