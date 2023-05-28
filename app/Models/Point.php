<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $guarded = false;
    public function owner(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
