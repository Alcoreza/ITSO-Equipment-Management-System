<?php 
namespace App\Models;

use CodeIgniter\Model;

class Reservations_model extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'email',
        'equipment_id',
        'reserve_date',
        'notes',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = false; // add created_at/updated_at if you want
}
?>
