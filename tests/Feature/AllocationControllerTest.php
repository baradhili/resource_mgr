<?php

namespace Tests\Feature;

use App\Models\Allocation;
use App\Models\Client;
use App\Models\Location;
use App\Models\Project;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AllocationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Unit test for the index method
     *
     * @group allocation
     * @group index
     */
    public function test_index_method()
    {
        $user = User::factory()->create();

        \Spatie\Permission\Models\Role::findOrCreate('super-admin', 'web');

        // add all permissions to 'super-admin'
        $role = \Spatie\Permission\Models\Role::findByName('super-admin');
        $role->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $user->assignRole('super-admin');

        // login the user
        $this->actingAs($user);

        // seed the db with some resources
        // first create ResourceType, Region & Location
        ResourceType::factory()->count(5)->create();
        Region::factory()->count(5)->create();
        Location::factory()->count(5)->create();
        Resource::factory()->count(10)->create();

        // create a project
        Client::factory()->count(10)->create();
        $project = Project::factory()->create();

        // // create some allocations
        Allocation::factory()->count(10)->create([
            'projects_id' => $project->id,
        ]);

        // // call the index method
        // $response = $this->get(route('allocations.index'));

        // // assert that we have the correct number of allocations
        // $this->assertCount(10, $response->original->resourceAllocationCollection);

        // // assert that we have the correct page
        // $this->assertEquals(1, $response->original->paginatedResourceAllocation->currentPage());
    }
}
