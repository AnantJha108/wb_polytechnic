<?php
// app/Models/State.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class State extends Model {
    public function districts() 
    { 
        return $this->hasMany(district::class); 
    }
}