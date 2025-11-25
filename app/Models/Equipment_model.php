<?php
namespace App\Models;

use CodeIgniter\Model;

class Equipment_model extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'equipment_name',
        'available'
    ];
}