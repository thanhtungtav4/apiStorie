<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testWelcomePage()
    {
        $response = $this->get('/welcome');
        $response->assertStatus(200);
        $response->assertSeeText('Welcome to Laravel!');
    }

}
