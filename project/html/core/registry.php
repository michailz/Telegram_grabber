<?php if (!defined("SYSTEM")) die('Error 404');

Class Registry Implements ArrayAccess  {
  private $vars = array();

  public function __construct() {
        $this->vars = array(
          'begin' => 1,
        );
  }
  public function set($key, $var) {
    if (isset($this->vars[$key]) == true) {
      throw new Exception('Unable to set var `' . $key . '`. Already set.');
    }
    $this->vars[$key] = $var;
    return true;
  }

  #[\ReturnTypeWillChange]
  public function get($key) {
    if (isset($this->vars[$key]) == false) {
      return null;
    }
    return $this->vars[$key];
  }

  public function remove($var) {
    unset($this->vars[$key]);
  }
  #[\ReturnTypeWillChange]
  public function offsetExists($offset) {
    return isset($this->vars[$offset]);
  }

  #[\ReturnTypeWillChange]
  public function offsetGet($offset) {
    return $this->get($offset);
  }

  #[\ReturnTypeWillChange]
  public function offsetSet($offset, $value) {
    $this->set($offset, $value);
  }

  #[\ReturnTypeWillChange]
  public function offsetUnset($offset) {
    unset($this->vars[$offset]);
  }

}
