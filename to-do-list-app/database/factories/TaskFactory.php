<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=> $this->faker->title(),
            'description'=>$this->faker->sentence(),
            'completed'=>$this->faker->boolean(),
            'user_id'=> User::factory(),
        ];
    }
}
