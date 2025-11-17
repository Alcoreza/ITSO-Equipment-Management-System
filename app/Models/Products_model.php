<?php
namespace App\Models;

use CodeIgniter\Model;

class Products_model extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'product_name',
        'description',
        'price',
        'category',
        'image',
        'created_at',
        'updated_at'
    ];
}
