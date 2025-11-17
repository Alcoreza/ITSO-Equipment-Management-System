<?php
namespace App\Models;

use CodeIgniter\Model;

class Users_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'role',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
?>