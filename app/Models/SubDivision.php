<?php
// app/Models/SubDivision.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SubDivision extends Model {
    public function blocks() 
    { 
        return $this->hasMany(Block::class); 
    }
}