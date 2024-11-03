<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Chirper extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic unit test example.
     */
    public function test_chirper_screen_can_be_rendered(): void
    {
        $response = $this->get('/chirps');

        $response->assertStatus(200);
    }


}
