<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model {

  protected $data;
  public $cols = [];

  public function addColumns($cols) {
      foreach ($cols as $col) {
          $this->addColumn($col);
      }
  }

  public function addColumn($args) {
      if(!isset($args['searchable'])) $args['searchable'] = false;
      array_push($this->cols, $args);
  }

  public function addRows($query) {
      $this->data = $query;
  }
}
