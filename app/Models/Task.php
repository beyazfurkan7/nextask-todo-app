<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['content', 'project_id', 'is_completed', 'due_date', 'priority', 'position'];
    protected $casts = ['is_completed' => 'boolean','due_date' => 'date',];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}



