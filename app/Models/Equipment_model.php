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
        'description',
    ];

    // Return results as arrays
    protected $returnType = 'array';

    // Must be typed as array to match CodeIgniter\BaseModel in PHP 8+
    protected array $casts = [
        'available' => 'integer',
    ];
}