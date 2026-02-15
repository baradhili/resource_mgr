<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to fix all table IDs and foreign keys to Laravel 11 standard.
 *
 * Laravel 11 standard uses:
 * - $table->id() for primary keys (creates unsignedBigInteger auto-increment)
 * - $table->foreignId() or $table->unsignedBigInteger() for foreign keys
 *
 * Tables with non-standard IDs:
 * - allocations: integer(id, true) → id()
 * - contracts: integer(id, true) → id()
 * - leaves: integer(id, true) → id()
 * - projects: integer(id, true) → id()
 * - resources: integer(id, true) → id()
 * - skills: integer(id, true) → id()
 * - teams: increments(id) → id()
 * - saml2_tenants: increments(id) → id()
 *
 * Foreign key columns updated to unsignedBigInteger:
 * - allocations.resources_id (references resources.id)
 * - allocations.projects_id (references projects.id) - NEW FK ADDED
 * - contracts.resources_id (references resources.id)
 * - leaves.resources_id (references resources.id)
 * - project_regions.project_id (references projects.id) - NEW FK ADDED
 * - project_service.project_id (references projects.id)
 * - resource_skill.resources_id (references resources.id)
 * - resource_skill.skills_id (references skills.id)
 * - staging_allocations.resources_id (references resources.id)
 * - staging_allocations.projects_id (references projects.id)
 * - staging_demands.projects_id (references projects.id)
 * - demands.project_id (references projects.id) - NEW FK ADDED
 * - users.resource_id (references resources.id)
 * - users.current_team_id (references teams.id)
 * - team_user.team_id (references teams.id)
 * - teams.owner_id (references users.id) - NEW FK ADDED
 * - teams.parent_team_id (references teams.id) - NEW FK ADDED
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop all affected foreign key constraints
        $this->dropForeignKeys();

        // Step 2: Modify primary key columns to unsignedBigInteger
        $this->modifyPrimaryKeys();

        // Step 3: Modify foreign key columns to unsignedBigInteger
        $this->modifyForeignKeys();

        // Step 4: Clean up orphan records before adding foreign keys
        $this->cleanupOrphanRecords();

        // Step 5: Re-add foreign key constraints (including new ones from models)
        $this->addForeignKeys();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Drop foreign key constraints (including new ones)
        $this->dropForeignKeysDown();

        // Step 2: Revert primary keys to original types
        $this->revertPrimaryKeys();

        // Step 3: Revert foreign key columns to original types
        $this->revertForeignKeys();

        // Step 4: Re-add original foreign key constraints (without new ones)
        $this->addOriginalForeignKeys();
    }

    /**
     * Check if a foreign key exists on a table.
     */
    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        $database = config('database.connections.mysql.database');
        $result = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE CONSTRAINT_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ? 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$database, $table, $foreignKey]);

        return count($result) > 0;
    }

    /**
     * Safely drop a foreign key if it exists.
     */
    private function dropForeignKeyIfExists(string $table, string $foreignKey): void
    {
        if ($this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }
    }

    /**
     * Clean up orphan records that would violate foreign key constraints.
     */
    private function cleanupOrphanRecords(): void
    {
        // Clean allocations with invalid resources_id
        DB::statement('UPDATE allocations SET resources_id = NULL WHERE resources_id IS NOT NULL AND resources_id NOT IN (SELECT id FROM resources)');
        
        // Clean allocations with invalid projects_id
        DB::statement('UPDATE allocations SET projects_id = NULL WHERE projects_id IS NOT NULL AND projects_id NOT IN (SELECT id FROM projects)');

        // Clean contracts with invalid resources_id
        DB::statement('UPDATE contracts SET resources_id = NULL WHERE resources_id IS NOT NULL AND resources_id NOT IN (SELECT id FROM resources)');

        // Clean leaves with invalid resources_id
        DB::statement('UPDATE leaves SET resources_id = NULL WHERE resources_id IS NOT NULL AND resources_id NOT IN (SELECT id FROM resources)');

        // Clean project_regions with invalid project_id
        DB::statement('DELETE FROM project_regions WHERE project_id IS NOT NULL AND project_id NOT IN (SELECT id FROM projects)');

        // Clean project_regions with invalid region_id
        DB::statement('DELETE FROM project_regions WHERE region_id IS NOT NULL AND region_id NOT IN (SELECT id FROM regions)');

        // Clean project_service with invalid project_id
        DB::statement('DELETE FROM project_service WHERE project_id IS NOT NULL AND project_id NOT IN (SELECT id FROM projects)');

        // Clean project_service with invalid service_id
        DB::statement('DELETE FROM project_service WHERE service_id IS NOT NULL AND service_id NOT IN (SELECT id FROM services)');

        // Clean resource_skill with invalid resources_id
        DB::statement('DELETE FROM resource_skill WHERE resources_id IS NOT NULL AND resources_id NOT IN (SELECT id FROM resources)');

        // Clean resource_skill with invalid skills_id
        DB::statement('DELETE FROM resource_skill WHERE skills_id IS NOT NULL AND skills_id NOT IN (SELECT id FROM skills)');

        // Clean staging_allocations with invalid resources_id
        DB::statement('UPDATE staging_allocations SET resources_id = NULL WHERE resources_id IS NOT NULL AND resources_id NOT IN (SELECT id FROM resources)');

        // Clean staging_allocations with invalid projects_id
        DB::statement('UPDATE staging_allocations SET projects_id = NULL WHERE projects_id IS NOT NULL AND projects_id NOT IN (SELECT id FROM projects)');

        // Clean staging_demands with invalid projects_id
        DB::statement('UPDATE staging_demands SET projects_id = NULL WHERE projects_id IS NOT NULL AND projects_id NOT IN (SELECT id FROM projects)');

        // Clean demands with invalid project_id
        DB::statement('UPDATE demands SET project_id = NULL WHERE project_id IS NOT NULL AND project_id NOT IN (SELECT id FROM projects)');

        // Clean users with invalid resource_id
        DB::statement('UPDATE users SET resource_id = NULL WHERE resource_id IS NOT NULL AND resource_id NOT IN (SELECT id FROM resources)');

        // Clean users with invalid current_team_id
        DB::statement('UPDATE users SET current_team_id = NULL WHERE current_team_id IS NOT NULL AND current_team_id NOT IN (SELECT id FROM teams)');

        // Clean team_user with invalid team_id
        DB::statement('DELETE FROM team_user WHERE team_id IS NOT NULL AND team_id NOT IN (SELECT id FROM teams)');

        // Clean team_user with invalid user_id
        DB::statement('DELETE FROM team_user WHERE user_id IS NOT NULL AND user_id NOT IN (SELECT id FROM users)');

        // Clean teams with invalid resource_type
        DB::statement('UPDATE teams SET resource_type = NULL WHERE resource_type IS NOT NULL AND resource_type NOT IN (SELECT id FROM resource_types)');

        // Clean teams with invalid owner_id
        DB::statement('UPDATE teams SET owner_id = NULL WHERE owner_id IS NOT NULL AND owner_id NOT IN (SELECT id FROM users)');

        // Clean teams with invalid parent_team_id
        DB::statement('UPDATE teams SET parent_team_id = NULL WHERE parent_team_id IS NOT NULL AND parent_team_id NOT IN (SELECT id FROM teams)');
    }

    /**
     * Drop all foreign key constraints that reference the affected tables.
     */
    private function dropForeignKeys(): void
    {
        // allocations table FK
        $this->dropForeignKeyIfExists('allocations', 'fk_allocations_resources1');
        $this->dropForeignKeyIfExists('allocations', 'fk_allocations_projects1');

        // contracts table FK
        $this->dropForeignKeyIfExists('contracts', 'fk_contract_resources');

        // leaves table FK
        $this->dropForeignKeyIfExists('leaves', 'fk_leave_resources1');

        // project_regions table FKs
        $this->dropForeignKeyIfExists('project_regions', 'project_regions_region_id_foreign');
        $this->dropForeignKeyIfExists('project_regions', 'project_regions_project_id_foreign');

        // project_service table FKs
        $this->dropForeignKeyIfExists('project_service', 'project_service_project_id_foreign');
        $this->dropForeignKeyIfExists('project_service', 'project_service_service_id_foreign');

        // resource_skill table FKs
        $this->dropForeignKeyIfExists('resource_skill', 'resource_skill_resources_id_foreign');
        $this->dropForeignKeyIfExists('resource_skill', 'resource_skill_skills_id_foreign');

        // staging_allocations table FKs
        $this->dropForeignKeyIfExists('staging_allocations', 'staging_allocations_projects_id_foreign');
        $this->dropForeignKeyIfExists('staging_allocations', 'staging_allocations_resources_id_foreign');

        // staging_demands table FK
        $this->dropForeignKeyIfExists('staging_demands', 'staging_demands_projects_id_foreign');

        // demands table FK
        $this->dropForeignKeyIfExists('demands', 'demands_project_id_foreign');

        // users table FK
        $this->dropForeignKeyIfExists('users', 'users_resource_id_foreign');

        // team_user table FKs
        $this->dropForeignKeyIfExists('team_user', 'team_user_team_id_foreign');
        $this->dropForeignKeyIfExists('team_user', 'team_user_user_id_foreign');

        // teams table FKs
        $this->dropForeignKeyIfExists('teams', 'teams_resource_type_foreign');
        $this->dropForeignKeyIfExists('teams', 'teams_owner_id_foreign');
        $this->dropForeignKeyIfExists('teams', 'teams_parent_team_id_foreign');
    }

    /**
     * Modify primary key columns to Laravel 11 standard (unsignedBigInteger).
     */
    private function modifyPrimaryKeys(): void
    {
        // allocations: integer → unsignedBigInteger
        Schema::table('allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // contracts: integer → unsignedBigInteger
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // leaves: integer → unsignedBigInteger
        Schema::table('leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // projects: integer → unsignedBigInteger
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // resources: integer → unsignedBigInteger
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // skills: integer → unsignedBigInteger
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // teams: unsignedInteger → unsignedBigInteger
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });

        // saml2_tenants: unsignedInteger → unsignedBigInteger
        Schema::table('saml2_tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
        });
    }

    /**
     * Modify foreign key columns to match the new primary key types.
     * Make columns nullable where orphan cleanup may set them to NULL.
     */
    private function modifyForeignKeys(): void
    {
        // allocations.resources_id and allocations.projects_id
        // Made nullable to allow orphan cleanup
        Schema::table('allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('resources_id')->nullable()->change();
            $table->unsignedBigInteger('projects_id')->nullable()->change();
        });

        // contracts.resources_id - made nullable for orphan cleanup
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('resources_id')->nullable()->change();
        });

        // leaves.resources_id - made nullable for orphan cleanup
        Schema::table('leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('resources_id')->nullable()->change();
        });

        // project_regions.project_id (references projects.id)
        Schema::table('project_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->change();
        });

        // project_service.project_id (references projects.id)
        Schema::table('project_service', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->change();
        });

        // resource_skill.resources_id and resource_skill.skills_id
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('resources_id')->change();
            $table->unsignedBigInteger('skills_id')->change();
        });

        // staging_allocations.resources_id and staging_allocations.projects_id
        Schema::table('staging_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('resources_id')->nullable()->change();
            $table->unsignedBigInteger('projects_id')->nullable()->change();
        });

        // staging_demands.projects_id
        Schema::table('staging_demands', function (Blueprint $table) {
            $table->unsignedBigInteger('projects_id')->nullable()->change();
        });

        // demands.project_id - already nullable in original migration
        // No change needed for demands.project_id as it's already unsignedBigInteger and nullable

        // users.resource_id
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('resource_id')->nullable()->change();
        });

        // users.current_team_id
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('current_team_id')->nullable()->change();
        });

        // team_user.team_id and team_user.user_id
        Schema::table('team_user', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });

        // teams.owner_id and teams.parent_team_id
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->change();
            $table->unsignedBigInteger('parent_team_id')->nullable()->change();
        });
    }

    /**
     * Safely add a foreign key if it doesn't already exist.
     */
    private function addForeignKeyIfNotExists(string $table, string $column, string $foreignKey, string $referencedTable, string $onDelete = 'no action'): void
    {
        if (!$this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $foreignKey, $referencedTable, $onDelete) {
                $blueprint->foreign($column, $foreignKey)
                    ->references('id')->on($referencedTable)
                    ->onUpdate('restrict')->onDelete($onDelete);
            });
        }
    }

    /**
     * Re-add all foreign key constraints including new ones from models.
     */
    private function addForeignKeys(): void
    {
        // allocations table FKs
        $this->addForeignKeyIfNotExists('allocations', 'resources_id', 'fk_allocations_resources1', 'resources', 'set null');
        $this->addForeignKeyIfNotExists('allocations', 'projects_id', 'fk_allocations_projects1', 'projects', 'set null');

        // contracts table FK
        $this->addForeignKeyIfNotExists('contracts', 'resources_id', 'fk_contract_resources', 'resources', 'set null');

        // leaves table FK
        $this->addForeignKeyIfNotExists('leaves', 'resources_id', 'fk_leave_resources1', 'resources', 'set null');

        // project_regions table FKs
        $this->addForeignKeyIfNotExists('project_regions', 'region_id', 'project_regions_region_id_foreign', 'regions', 'cascade');
        $this->addForeignKeyIfNotExists('project_regions', 'project_id', 'project_regions_project_id_foreign', 'projects', 'cascade');

        // project_service table FKs
        $this->addForeignKeyIfNotExists('project_service', 'project_id', 'project_service_project_id_foreign', 'projects', 'cascade');
        $this->addForeignKeyIfNotExists('project_service', 'service_id', 'project_service_service_id_foreign', 'services', 'cascade');

        // resource_skill table FKs
        $this->addForeignKeyIfNotExists('resource_skill', 'resources_id', 'resource_skill_resources_id_foreign', 'resources', 'cascade');
        $this->addForeignKeyIfNotExists('resource_skill', 'skills_id', 'resource_skill_skills_id_foreign', 'skills', 'cascade');

        // staging_allocations table FKs
        $this->addForeignKeyIfNotExists('staging_allocations', 'projects_id', 'staging_allocations_projects_id_foreign', 'projects', 'set null');
        $this->addForeignKeyIfNotExists('staging_allocations', 'resources_id', 'staging_allocations_resources_id_foreign', 'resources', 'set null');

        // staging_demands table FK
        $this->addForeignKeyIfNotExists('staging_demands', 'projects_id', 'staging_demands_projects_id_foreign', 'projects', 'set null');

        // demands table FK for project_id
        $this->addForeignKeyIfNotExists('demands', 'project_id', 'demands_project_id_foreign', 'projects', 'set null');

        // users table FK
        $this->addForeignKeyIfNotExists('users', 'resource_id', 'users_resource_id_foreign', 'resources', 'set null');

        // team_user table FKs
        $this->addForeignKeyIfNotExists('team_user', 'team_id', 'team_user_team_id_foreign', 'teams', 'cascade');
        $this->addForeignKeyIfNotExists('team_user', 'user_id', 'team_user_user_id_foreign', 'users', 'cascade');

        // teams table FKs
        $this->addForeignKeyIfNotExists('teams', 'resource_type', 'teams_resource_type_foreign', 'resource_types', 'set null');
        $this->addForeignKeyIfNotExists('teams', 'owner_id', 'teams_owner_id_foreign', 'users', 'set null');
        $this->addForeignKeyIfNotExists('teams', 'parent_team_id', 'teams_parent_team_id_foreign', 'teams', 'set null');
    }

    /**
     * Drop all foreign key constraints for down migration (including new ones).
     */
    private function dropForeignKeysDown(): void
    {
        // Use the same safe drop method as up migration
        $this->dropForeignKeys();
    }

    /**
     * Re-add original foreign key constraints (without new ones) for down migration.
     */
    private function addOriginalForeignKeys(): void
    {
        // allocations table FK (resources_id only - original)
        Schema::table('allocations', function (Blueprint $table) {
            $table->foreign('resources_id', 'fk_allocations_resources1')
                ->references('id')->on('resources')
                ->onUpdate('no action')->onDelete('no action');
        });

        // contracts table FK
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('resources_id', 'fk_contract_resources')
                ->references('id')->on('resources')
                ->onUpdate('no action')->onDelete('no action');
        });

        // leaves table FK
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('resources_id', 'fk_leave_resources1')
                ->references('id')->on('resources')
                ->onUpdate('no action')->onDelete('no action');
        });

        // project_regions table FK (region_id only - original)
        Schema::table('project_regions', function (Blueprint $table) {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onUpdate('restrict')->onDelete('cascade');
        });

        // project_service table FKs
        Schema::table('project_service', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onUpdate('restrict')->onDelete('cascade');
        });

        // resource_skill table FKs
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->foreign('resources_id')
                ->references('id')->on('resources')
                ->onUpdate('no action')->onDelete('cascade');
            $table->foreign('skills_id')
                ->references('id')->on('skills')
                ->onUpdate('no action')->onDelete('cascade');
        });

        // staging_allocations table FKs
        Schema::table('staging_allocations', function (Blueprint $table) {
            $table->foreign('projects_id')
                ->references('id')->on('projects')
                ->onUpdate('restrict')->onDelete('set null');
            $table->foreign('resources_id')
                ->references('id')->on('resources')
                ->onUpdate('restrict')->onDelete('set null');
        });

        // staging_demands table FK
        Schema::table('staging_demands', function (Blueprint $table) {
            $table->foreign('projects_id')
                ->references('id')->on('projects')
                ->onUpdate('restrict')->onDelete('set null');
        });

        // users table FK
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('resource_id')
                ->references('id')->on('resources')
                ->onUpdate('restrict')->onDelete('cascade');
        });

        // team_user table FKs
        Schema::table('team_user', function (Blueprint $table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onUpdate('restrict')->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        // teams table FK (resource_type only - original)
        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('resource_type')
                ->references('id')->on('resource_types')
                ->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Revert primary keys to original types (for down migration).
     */
    private function revertPrimaryKeys(): void
    {
        // allocations: unsignedBigInteger → integer
        Schema::table('allocations', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // contracts: unsignedBigInteger → integer
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // leaves: unsignedBigInteger → integer
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // projects: unsignedBigInteger → integer
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // resources: unsignedBigInteger → integer
        Schema::table('resources', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // skills: unsignedBigInteger → integer
        Schema::table('skills', function (Blueprint $table) {
            $table->integer('id', true)->change();
        });

        // teams: unsignedBigInteger → unsignedInteger
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->change();
        });

        // saml2_tenants: unsignedBigInteger → unsignedInteger
        Schema::table('saml2_tenants', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->change();
        });
    }

    /**
     * Revert foreign key columns to original types (for down migration).
     */
    private function revertForeignKeys(): void
    {
        // allocations.resources_id and allocations.projects_id
        Schema::table('allocations', function (Blueprint $table) {
            $table->integer('resources_id')->change();
            $table->integer('projects_id')->change();
        });

        // contracts.resources_id
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('resources_id')->change();
        });

        // leaves.resources_id
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('resources_id')->change();
        });

        // project_regions.project_id
        Schema::table('project_regions', function (Blueprint $table) {
            $table->integer('project_id')->change();
        });

        // project_service.project_id
        Schema::table('project_service', function (Blueprint $table) {
            $table->integer('project_id')->change();
        });

        // resource_skill.resources_id and resource_skill.skills_id
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->integer('resources_id')->change();
            $table->integer('skills_id')->change();
        });

        // staging_allocations.resources_id and staging_allocations.projects_id
        Schema::table('staging_allocations', function (Blueprint $table) {
            $table->integer('resources_id')->nullable()->change();
            $table->integer('projects_id')->nullable()->change();
        });

        // staging_demands.projects_id
        Schema::table('staging_demands', function (Blueprint $table) {
            $table->integer('projects_id')->nullable()->change();
        });

        // users.resource_id
        Schema::table('users', function (Blueprint $table) {
            $table->integer('resource_id')->nullable()->change();
        });

        // users.current_team_id
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('current_team_id')->nullable()->change();
        });

        // team_user.team_id and team_user.user_id
        Schema::table('team_user', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });

        // teams.owner_id and teams.parent_team_id
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedInteger('owner_id')->nullable()->change();
            $table->unsignedInteger('parent_team_id')->nullable()->change();
        });
    }
};