<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsActivity extends Model
{   
    protected $table = 'logs_activity'; 
    
    protected $fillable = ['model', 'model_id', 'event', 'data'];
}
