<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;

class Address extends Model
{
    use HasFactory, SoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'module_id',
        'module_type',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'postal_code',
        'country',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function module()
    {
        return $this->morphTo();
    }
}
