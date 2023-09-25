<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'user_id', 'type'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    // protected $dateFormat = 'U';
    protected $casts = [
        'created_at' => 'datetime:d/M/y h:i A',
        'updated_at' => 'datetime:d/M/y h:i A',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
