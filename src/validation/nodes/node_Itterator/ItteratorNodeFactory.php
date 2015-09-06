<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ItteratorNodeFactory extends ValidationNodeFactory{
	
	public function __construct() {
		parent::__construct("CORE\\itterator", ItteratorNode::class);
	}
	
	public function generate() {
		return new ItteratorNode();
	}

}