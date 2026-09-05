<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_tasks(): void
    {
        Task::factory()->count(2)->create();

        $response = $this->getJson('/api/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_create_task(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Learn GitHub Actions',
            'description' => 'Build a CI pipeline',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('title', 'Learn GitHub Actions');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Learn GitHub Actions',
        ]);
    }

    public function test_title_is_required(): void
    {
        $response = $this->postJson('/api/tasks', [
            'description' => 'Test validation',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_can_show_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response
            ->assertStatus(200)
            ->assertJsonPath('id', $task->id);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
