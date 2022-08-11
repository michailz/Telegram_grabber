<?php if (!defined("SYSTEM")) die('Error 404');

Abstract Class Controller_Base {
  protected $registry;

  function __construct($registry) {
    $this->registry = $registry;
  }

  abstract function index();

}
