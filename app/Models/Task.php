<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'assigned_to','status'];
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

