<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'course', 'status', 'image', 'address',
    ];

    // Status constants
    const STATUS_ACTIVE    = 'active';
    const STATUS_INACTIVE  = 'inactive';
    const STATUS_GRADUATED = 'graduated';
    const STATUS_SUSPENDED = 'suspended';

    // Status badge color (Tailwind)
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE    => 'bg-green-50 text-green-700 border-green-200',
            self::STATUS_INACTIVE  => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            self::STATUS_GRADUATED => 'bg-blue-50 text-blue-700 border-blue-200',
            self::STATUS_SUSPENDED => 'bg-red-50 text-red-700 border-red-200',
            default                => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    // Status label
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    // Combined status (status + soft delete)
    public function getOverallStatusLabelAttribute(): string
    {
        if ($this->trashed()) {
            return 'Deleted (' . ucfirst($this->status) . ')';
        }
        return ucfirst($this->status);
    }

    public function getOverallStatusBadgeClassAttribute(): string
    {
        if ($this->trashed()) {
            return 'bg-red-50 text-red-700 border-red-200';
        }
        return $this->status_badge_class;
    }
}