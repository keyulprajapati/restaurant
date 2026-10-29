<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RestaurantTableBranchUniquenessTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_numbers_may_repeat_across_branches_but_not_within_a_branch(): void
    {
        $user = User::factory()->create(['all_branches' => true]);
        $user->givePermissionTo(Permission::findOrCreate('tables.create', 'web'));

        $firstBranch = $this->createBranch('First Branch', 'FB');
        $secondBranch = $this->createBranch('Second Branch', 'SB');

        $this->actingAs($user)
            ->post(route('admin.tables.store'), $this->tableData($firstBranch->id))
            ->assertRedirect(route('admin.tables.index'));

        $this->post(route('admin.tables.store'), $this->tableData($secondBranch->id))
            ->assertRedirect(route('admin.tables.index'));

        $this->post(route('admin.tables.store'), $this->tableData($firstBranch->id))
            ->assertSessionHasErrors('table_number');
    }

    private function createBranch(string $name, string $code): Branch
    {
        return Branch::create([
            'name' => $name,
            'code' => $code,
            'is_active' => true,
        ]);
    }

    private function tableData(int $branchId): array
    {
        return [
            'branch_id' => $branchId,
            'table_number' => 'T-01',
            'name' => 'Table 1',
            'capacity' => 4,
            'table_type' => 'regular',
        ];
    }
}
