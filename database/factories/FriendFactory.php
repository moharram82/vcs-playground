<?php

namespace Database\Factories;

use App\Models\Friend;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FriendFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friend::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'requester_id' => User::factory(),
            'invited_id' => User::factory(),
        ];
    }

    public function request()
    {
        return $this->state(
            function () {
                return [
                    'accepted_at' => null,
                    'is_request' => true,
                ];
            }
        );
    }

    public function accepted()
    {
        return $this->state(
            function () {
                return [
                    'accepted_at' => now(),
                    'is_request' => false,
                ];
            }
        );
    }
}
