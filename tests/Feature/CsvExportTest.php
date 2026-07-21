<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_trigger_csv_export()
    {
        $response = $this->postJson('/export/csv', [
            'report_type' => 'Analytics Report'
        ]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_export_csv_package()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/export/csv', [
            'report_type' => 'Analytics Report',
            'title' => 'Analytics CSV Export',
            'return_json' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'report_type',
            'title',
            'csv_content',
            'file_name'
        ]);
        $response->assertJson([
            'success' => true,
            'export_format' => 'CSV'
        ]);
    }

    public function test_authenticated_user_can_export_png_placeholder()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/export/png', [
            'report_type' => 'Executive Report',
            'title' => 'Executive PNG Snapshot'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'export_format' => 'PNG'
        ]);
    }
}
