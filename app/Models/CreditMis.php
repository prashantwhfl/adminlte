<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class CreditMis extends Model
{
    //protected $connection = 'mysql'; //pass the connection name here
   /*  protected $fillable = [
        'id', 'month', 'compliance_type', 'responsibility', 'due_date', 'filling_date', 'remarks'
    ]; */
    public $timestamps = false;
    protected $table = 'credit_mis';

    protected $connection = 'mysql3';
}
