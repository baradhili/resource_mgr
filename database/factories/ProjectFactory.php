<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'empowerID' => $this->faker->regexify('[A-Z0-9]{20}'),
            'name' => $this->faker->company,
            'projectManager' => $this->faker->name,
            'status' => $this->faker->randomElement(['Proposed', 'Active', 'Cancelled', 'Completed', 'On Hold', 'Prioritised']),
        ];
    }
}
