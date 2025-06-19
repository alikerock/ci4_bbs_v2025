<?php
  namespace App\Models;
  use CodeIgniter\Model;

  class BoardModel extends Model
  {
    protected $table = 'ci4board';
    protected $primaryKey = 'bid';
    protected $returnType  = 'object';
    protected $allowedFields = [
      'userid', 
      'subject',
      'content',
      'regdate'
    ];
  }