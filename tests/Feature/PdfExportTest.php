<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_trigger_pdf_export()
    {
        $response = $this->postJson('/export/pdf', [
            'report_type' => 'Executive Report',
            'title' => 'Test PDF'
        ]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_export_pdf_json_package()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/export/pdf', [
            'report_type' => 'Executive Report',
            'title' => 'Test PDF Profile',
            'return_json' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'report_type',
            'title',
            'html_content',
            'file_name'
        ]);
        $response->assertJson([
            'success' => true,
            'export_format' => 'PDF'
        ]);
    }

    public function test_authenticated_user_can_export_print_layout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/export/print', [
            'report_type' => 'Analytics Report',
            'title' => 'Print Audit Layout',
            'return_json' => true
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'export_format' => 'PRINT'
        ]);
    }
}
