<?php namespace storm\actions;

class ValidationProfileNodeFactory extends ValidationNodeFactory{
	
	public function __construct() {
		parent::__construct("CORE\\external", ValidationProfileNode::class);
	}
	
	public function generate() {
		return new ValidationProfileNode();
	}

}