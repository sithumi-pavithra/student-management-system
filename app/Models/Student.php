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

    const STATUS_ACTIVE    = 'active';
    const STATUS_INACTIVE  = 'inactive';
    const STATUS_GRADUATED = 'graduated';
    const STATUS_SUSPENDED = 'suspended';

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE    => 'bg-success bg-opacity-10 text-success border border-success',
            self::STATUS_INACTIVE  => 'bg-warning bg-opacity-10 text-warning border border-warning',
            self::STATUS_GRADUATED => 'bg-primary bg-opacity-10 text-primary border border-primary',
            self::STATUS_SUSPENDED => 'bg-danger bg-opacity-10 text-danger border border-danger',
            default                => 'bg-secondary bg-opacity-10 text-secondary border border-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

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
            return 'bg-danger bg-opacity-10 text-danger border border-danger';
        }
        return $this->status_badge_class;
    }
}