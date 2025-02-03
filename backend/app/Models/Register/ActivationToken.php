<?php

namespace App\Models\Register;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivityTrait;

class ActivationToken extends Model
{
    use HasFactory,LogsActivityTrait;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relacionamento com o modelo User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
