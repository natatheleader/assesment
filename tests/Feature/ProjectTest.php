<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Attribute;
use App\Models\AttributeValues;
use Laravel\Passport\Passport;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        // Create test attributes
        Attribute::create(['name' => 'department', 'type' => 'text']);
        Attribute::create(['name' => 'budget', 'type' => 'number']);
        Attribute::create(['name' => 'start_date', 'type' => 'date']);

        // Authenticate user
        Passport::actingAs($this->user);
    }

    /** @test */
    public function it_can_list_all_projects()
    {
        $project = Project::create(['name' => 'Test Project', 'status' => 'active']);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
                ->assertJsonStructure(['data'])
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_filter_projects_by_regular_attributes()
    {
        Project::create(['name' => 'Project A', 'status' => 'active']);
        Project::create(['name' => 'Project B', 'status' => 'pending']);

        $response = $this->getJson('/api/projects?filters[status]=active');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_filter_projects_by_eav_attributes()
    {
        $project = Project::create(['name' => 'IT Project', 'status' => 'active']);
        $department = Attribute::where('name', 'department')->first();
        
        AttributeValues::create([
            'attribute_id' => $department->id,
            'entity_id' => $project->id,
            'value' => 'IT'
        ]);

        $response = $this->getJson('/api/projects?filters[department]=IT');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_filter_projects_by_numeric_comparison()
    {
        $project = Project::create(['name' => 'High Budget', 'status' => 'active']);
        $budget = Attribute::where('name', 'budget')->first();
        
        AttributeValues::create([
            'attribute_id' => $budget->id,
            'entity_id' => $project->id,
            'value' => '100000'
        ]);

        $response = $this->getJson('/api/projects?filters[budget][operator]=>&filters[budget][value]=50000');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_create_project_with_attributes()
    {
        $data = [
            'name' => 'New Project',
            'status' => 'active',
            'attributes' => [
                'department' => 'IT',
                'budget' => '75000'
            ]
        ];

        $response = $this->postJson('/api/projects', $data);

        $response->assertStatus(201)
                ->assertJsonStructure(['data' => ['name', 'status', 'attributes']])
                ->assertJson(['data' => ['name' => 'New Project']]);

        $this->assertDatabaseHas('projects', ['name' => 'New Project']);
        $this->assertDatabaseHas('attribute_values', ['value' => 'IT']);
    }

    /** @test */
    public function it_can_update_project_with_attributes()
    {
        $project = Project::create(['name' => 'Old Name', 'status' => 'active']);

        $data = [
            'name' => 'Updated Name',
            'attributes' => [
                'department' => 'Marketing'
            ]
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $data);

        $response->assertStatus(200)
                ->assertJson(['data' => ['name' => 'Updated Name']]);

        $this->assertDatabaseHas('projects', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_project()
    {
        $project = Project::create(['name' => 'To Delete', 'status' => 'active']);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/projects', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'status']);
    }

    /** @test */
    public function it_can_filter_by_like_operator()
    {
        Project::create(['name' => 'Marketing Project', 'status' => 'active']);
        Project::create(['name' => 'IT Project', 'status' => 'active']);

        $response = $this->getJson('/api/projects?filters[name][operator]=LIKE&filters[name][value]=Market');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');
    }
}