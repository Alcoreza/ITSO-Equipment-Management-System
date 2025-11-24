<?php 
namespace App\Models;

use CodeIgniter\Model;

class Returned_model extends Model
{
    protected $table = 'returned_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'borrow_id',
        'borrower_id',
        'email',
        'equipment_id',
        'return_date'
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = false;
}
?>