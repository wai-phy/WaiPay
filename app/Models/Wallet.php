<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];  

    public function user(){
        return $this->belongsTO(User::class,'user_id', 'id');
    }

}
