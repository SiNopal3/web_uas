<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExcelExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_trigger_excel_export()
    {
        $response = $this->postJson('/export/excel', [
            'report_type' => 'System Health Report'
        ]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_export_excel_package()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/export/excel', [
            'report_type' => 'System Health Report',
            'title' => 'System Uptime Excel',
            'return_json' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'report_type',
            'title',
            'csv_content',
            'excel_html',
            'sheet_count'
        ]);
        $response->assertJson([
            'success' => true,
            'export_format' => 'EXCEL'
        ]);
    }
}
