<?php
// app/Models/Employee.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = []; // simplify for this large form

    public function academicDetails() 
    { 
        return $this->hasMany(EmployeeAcademicDetail::class);
    }

    public function higherStudies()   
    {
         return $this->hasMany(EmployeeHigherStudy::class); 
    }

    public function principalIncharges() 
    { 
        return $this->hasMany(EmployeePrincipalIncharge::class); 
    }

    public function deputations()     
    { 
        return $this->hasMany(EmployeeDeputation::class); 
    }

    public function college()         
    { 
        return $this->belongsTo(College::class); 
    }
    
}