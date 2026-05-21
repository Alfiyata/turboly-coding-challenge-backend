<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'due_date',
        'priority',
        'completed',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'completed' => 'boolean'
        ];
    }
}
