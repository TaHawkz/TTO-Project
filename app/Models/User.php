<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'department', 'designation', 'phone', 'is_active',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isSystemAdmin(): bool
    {
        return $this->role === 'system_admin';
    }

    public function isTTOStaff(): bool
    {
        return in_array($this->role, ['tto_officer', 'legal_officer', 'director', 'system_admin']);
    }

    public function canReviewDisclosures(): bool
    {
        return in_array($this->role, ['reviewer', 'tto_officer', 'legal_officer', 'director', 'system_admin']);
    }

    public function canManagePatents(): bool
    {
        return in_array($this->role, ['tto_officer', 'legal_officer', 'director', 'system_admin']);
    }

    public function disclosures(): HasMany
    {
        return $this->hasMany(Disclosure::class, 'submitted_by');
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'student'       => 'Student',
            'faculty'       => 'Faculty',
            'staff'         => 'Staff',
            'reviewer'      => 'Reviewer',
            'tto_officer'   => 'TTO Officer',
            'legal_officer' => 'Legal Officer',
            'director'      => 'Director',
            'system_admin'  => 'System Admin',
            default         => ucfirst($this->role),
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'system_admin'  => 'bg-red-100 text-red-800',
            'director'      => 'bg-purple-100 text-purple-800',
            'tto_officer'   => 'bg-teal-100 text-teal-800',
            'legal_officer' => 'bg-blue-100 text-blue-800',
            'reviewer'      => 'bg-yellow-100 text-yellow-800',
            'faculty'       => 'bg-green-100 text-green-800',
            'staff'         => 'bg-orange-100 text-orange-800',
            default         => 'bg-gray-100 text-gray-700',
        };
    }
}
