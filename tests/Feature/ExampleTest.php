<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * A basic test example.
     */
    public function test_the_homepage_redirects_to_public_ticket_form(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/solicitacoes/nova');
    }

    public function test_the_public_ticket_form_returns_a_successful_response(): void
    {
        $response = $this->get('/solicitacoes/nova');

        $response->assertStatus(200);
    }
}
