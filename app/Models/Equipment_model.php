<?php

namespace App\Models;
use CodeIgniter\Model;

class Equipment_model extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';

    protected $allowedFields = [
        'equipment_name',
        'equipment_type',
        'available',
        'status',
    ];

    // Optional: return results as arrays
    protected $returnType = 'array';
}
