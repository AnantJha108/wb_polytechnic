<?php
// app/Models/District.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class District extends Model {
    public function subDivisions() 
    { 
        return $this->hasMany(SubDivision::class); 
    }
}