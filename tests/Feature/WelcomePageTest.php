<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomePageTest extends TestCase
{
    /**
     * Test the welcome page loads successfully.
     */
    public function test_welcome_page_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test the welcome page contains expected content.
     */
    public function test_welcome_page_contains_laravel_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel');
    }

    /**
     * Test the welcome page has proper HTML structure.
     */
    public function test_welcome_page_has_proper_html_structure(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<html', false);
        $response->assertSee('<title>', false);
        $response->assertSee('</html>', false);
    }

    /**
     * Test the welcome page includes CSS styling.
     */
    public function test_welcome_page_includes_styling(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Check for styling - either inline styles or linked stylesheets
        $content = $response->getContent();
        $hasStyles = str_contains($content, '<style>') || 
                     str_contains($content, 'stylesheet') ||
                     str_contains($content, '.css');
        
        $this->assertTrue($hasStyles, 'Welcome page should include CSS styling');
    }
}