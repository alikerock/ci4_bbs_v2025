<?php
  namespace App\Models;
  use CodeIgniter\Model;

  class MemberModel extends Model
  {
    protected $table = 'members';
    protected $primaryKey = 'idx';
    protected $returnType  = 'object';
    protected $allowedFields = [
      'userid',
      'email',
      'username',
      'passwd',
      'regdate',
      'level',
      'last_login',
      'end_login_date'
    ];
  }