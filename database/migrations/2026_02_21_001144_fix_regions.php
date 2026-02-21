<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Resource;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Resource::whereNull('region_id')
            ->orWhereDoesntHave('region')
            ->chunkById(100, function ($resources) {
                foreach ($resources as $resource) {
                    // Skip if location or location's region is missing
                    if (!$resource->location?->region) {
                        continue;
                    }
                    
                    // Use updateQuietly() to avoid triggering model events [[53]]
                    $resource->updateQuietly([
                        'region_id' => $resource->location->region->id
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // too dangerous to try and reverse it...
    }
};
