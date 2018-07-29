<?php
class Builder {
  public $expression;
  public function __construct() {
    $this->expression = '';
  }
  public function WhiteSpace() {
    $this->expression .= "\s*";
    return $this;
  }
  public function Tags( $tagName, $attributes  = false, $homo = false ) {
    if ( $attributes ) $attr = $attributes . "\s*=['|\"]\s*(.*)\s*['|\"]\s*";
    else $attr = "";
    if ( $homo ) $homo = '\s*\/\s*';
    else $homo = '';
    $this->expression .= "[<^]\s*".$tagName."\s*".$attr.$homo."[>$]";
    return $this;
  }
  public function ClosingTags( $tagName ) {
    $this->expression .= "[<^]\s*\/\s*".$tagName."\s*[>$]";
    return $this;
  }
  public function Make() {
    $returnable = "/" 
      . $this->expression
      . "/Ui";
    $this->expression = '';
    return $returnable;
  }
}