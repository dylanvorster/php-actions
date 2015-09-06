<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ConditionalValidationNodeFactory extends ValidationNodeFactory{
	
	public function __construct() {
		parent::__construct("CORE\\condition", ConditionalValidationNode::class);
	}
	
	public function generate() {
		return new ConditionalValidationNode(ConditionalValidationNode::TYPE_EQUALS,0);
	}

}