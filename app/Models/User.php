<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


#[Fillable(['name', 'email', 'password', 'branch_id', 'all_branches'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'all_branches' => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class)->withTimestamps();
    }

    public function allowedBranchIds(): ?array
    {
        if ($this->all_branches) {
            return null;
        }

        $branchIds = $this->branches()->pluck('branches.id')->all();

        if ($this->branch_id && !in_array($this->branch_id, $branchIds)) {
            $branchIds[] = $this->branch_id;
        }

        return $branchIds;
    }

    public function canAccessBranch(int|string $branchId): bool
    {
        $allowedBranchIds = $this->allowedBranchIds();

        return $allowedBranchIds === null || in_array((int) $branchId, array_map('intval', $allowedBranchIds), true);
    }
}
