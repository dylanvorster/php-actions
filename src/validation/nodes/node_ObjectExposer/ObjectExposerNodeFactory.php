<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ObjectExposerNodeFactory extends ValidationNodeFactory{
	
	public function __construct() {
		parent::__construct("CORE\\exposer", ObjectExposerNode::class);
	}
	
	public function generate() {
		return new ObjectExposerNode("toString");
	}

}