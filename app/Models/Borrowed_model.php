<?php 
namespace App\Models;

use CodeIgniter\Model;

class Borrowed_model extends Model
{
    protected $table = 'borrowed_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
    'borrower_id',
    'email',
    'equipment_id',
    'return_date',
    'status'
    ];


    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = false;
}
?>