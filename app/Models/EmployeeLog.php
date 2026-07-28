<?php
// app/Models/EmployeeLog.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EmployeeLog extends Model
{
    protected $fillable = ['employee_id', 'action', 'reason', 'performed_by', 'ip_address'];
    public function performer() { return $this->belongsTo(User::class, 'performed_by'); }
}