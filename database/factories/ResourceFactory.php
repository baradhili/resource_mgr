<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'empowerID' => $this->faker->unique()->regexify('[A-Z0-9_-]{3,30}'),
            'user_id' => User::pluck('id')->random(),
            'resource_type' => ResourceType::pluck('id')->random(),
            'baseAvailability' => $this->faker->numberBetween(0, 100) / 100,
            'region_id' => Region::pluck('id')->random(),
            'location_id' => Location::pluck('id')->random(),
        ];
    }
}
