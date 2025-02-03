<?php

namespace App\Models\TravelOrders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Register\User;
use App\Traits\TracksUserActions;
use App\Traits\LogsActivityTrait;

class TravelOrder extends Model
{
    use HasFactory, SoftDeletes, TracksUserActions, LogsActivityTrait;

    protected $fillable = [
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
}

}