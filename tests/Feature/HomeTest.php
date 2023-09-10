<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_home_page(): void
    {
        $response = $this->get('/');

        $response->assertSeeText('Home page');
        $response->assertStatus(200);
    }

    public function test_contact_page(): void
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact');
        $response->assertStatus(200);
    }
}
