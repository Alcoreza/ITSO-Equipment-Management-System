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
        'return_date'
    ];

    protected bool $allowEmptyInserts = false;

    // If you want timestamps, set to true.
    // Your table does NOT include created_at / updated_at,
    // so we keep timestamps disabled.
    protected $useTimestamps = false;
}
?>
