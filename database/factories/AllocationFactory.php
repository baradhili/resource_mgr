<?php

namespace Database\Factories;

use App\Models\Allocation;
use App\Models\Project;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Allocation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'allocation_date' => $this->faker->date(),
            'fte' => $this->faker->numberBetween(1, 100),
            'resources_id' => Resource::factory(),
            'projects_id' => Project::factory(),
            'status' => $this->faker->randomElement(['Proposed','Committed']),
            'source' => $this->faker->randomElement(['Imported','Manual']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Allocation $allocation) {
            if ($project = $this->project) {
                $allocation->projects_id = $project->id;
            }
        });
    }

    public function project(Project $project)
    {
        return $this->state(['projects_id' => $project->id]);
    }
}

