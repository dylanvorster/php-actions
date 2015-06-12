<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ConditionalValidationNodeFactory extends ValidationNodeFactory{
	
	public function __construct() {
		parent::__construct("ACTIONS/CONDITIONAL_VALIDATION", ConditionalValidationNode::class);
	}
	
	public function generate() {
		return new ConditionalValidationNode(ConditionalValidationNode::TYPE_EQUALS,0);
	}

}