<?php

namespace App\Models\Register;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksUserActions;
use App\Traits\LogsActivityTrait;

class Company extends Model
{
    use HasFactory, SoftDeletes, TracksUserActions,LogsActivityTrait;

    protected $fillable = [
        'name', 
        'cnpj', 
        'company_type', 
        'responsible_name', 
        'responsible_cpf', 
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relacionamento com os Usuários
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'module');
    }

}

