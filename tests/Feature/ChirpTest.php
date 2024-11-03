<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

class ChirpTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexDisplaysChirps()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/chirps');

        $response->assertOk();
    }

    public function testStoreCreatesChirp()
    {
        $user = User::factory()->create();

        // Act as the user and send a POST request to store a chirp
        $response = $this->actingAs($user)->post(route('chirps.store'), [
            'message' => 'This is a test chirp',
        ]);

        // Assert it redirects back to the index
        $response->assertRedirect(route('chirps.index'));

        // Assert the chirp is in the database
        $this->assertDatabaseHas('chirps', [
            'message' => 'This is a test chirp',
            'user_id' => $user->id,
        ]);
    }

    public function testUpdateChirp()
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->create(['user_id' => $user->id]);

        // Act as the user and send a PUT request to update the chirp
        $response = $this->actingAs($user)->put(route('chirps.update', $chirp), [
            'message' => 'Updated chirp message',
        ]);

        $response->assertRedirect(route('chirps.index'));

        // Verify the chirp was updated
        $this->assertDatabaseHas('chirps', [
            'id' => $chirp->id,
            'message' => 'Updated chirp message',
        ]);
    }

    public function testDeleteChirp()
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->create(['user_id' => $user->id]);

        // Act as the user and delete the chirp
        $response = $this->actingAs($user)->delete(route('chirps.destroy', $chirp));

        $response->assertRedirect(route('chirps.index'));

        // Verify the chirp was deleted
        $this->assertDatabaseMissing('chirps', [
            'id' => $chirp->id,
        ]);
    }

    public function testUnauthorizedUserCannotUpdateOrDeleteChirp()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $chirp = Chirp::factory()->create(['user_id' => $otherUser->id]);

        // Act as an unauthorized user and try to update the chirp
        $response = $this->actingAs($user)->put(route('chirps.update', $chirp), [
            'message' => 'Attempt to update',
        ]);

        $response->assertStatus(403);

        // Act as an unauthorized user and try to delete the chirp
        $response = $this->actingAs($user)->delete(route('chirps.destroy', $chirp));

        $response->assertStatus(403);
    }
}
