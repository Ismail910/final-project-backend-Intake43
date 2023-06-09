<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\managers;
class Project extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'project_title',
        'project_type',
        'project_description',
        'project_start',
        'project_end',
        'project_status',
        'ProductOwner_id',
        'ProductManager_id',
        'client_id',
    ];
    function ProductOwner()
    {
        return $this->belongsTo(managers::class,'ProductOwner_id','id');
    }
    function ProductManager()
    {
        return $this->belongsTo(managers::class,'ProductManager_id','id');
    }
    function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }
}
