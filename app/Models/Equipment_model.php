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
        'image',
        'description'
    ];

    // Return results as arrays
    protected $returnType = 'array';
    
    // Enable timestamps if you have created_at/updated_at columns
    // protected $useTimestamps = true;
}