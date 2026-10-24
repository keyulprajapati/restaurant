<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\RestaurantTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_branch_can_be_created_and_attached_to_table(): void
    {
        $branch = Branch::create([
            'name' => 'Downtown Branch',
            'code' => 'DT',
            'phone' => '1234567890',
            'address' => 'Main Road',
            'is_active' => true,
        ]);

        $table = RestaurantTable::create([
            'branch_id' => $branch->id,
            'table_number' => 'T-101',
            'name' => 'Window Table',
            'capacity' => 4,
            'area' => 'Main Hall',
            'table_type' => 'regular',
            'status' => 'available',
            'is_active' => true,
        ]);

        $this->assertEquals($branch->id, $table->fresh()->branch_id);
        $this->assertEquals('Downtown Branch', $table->branch->name);
    }
}
