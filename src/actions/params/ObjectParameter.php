<?php namespace storm\actions;
/**
 * An array Parameter represents an array of input values as a single parameter.
 * It essentially models any existing parameter, but it encodes the values into
 * sets while maintaining the original key, but with the new encoded value
 * 
 * @author Dylan Vorster
 */
class ObjectParameter extends Parameter{
	
	protected $class;

	public function __construct($name,$class, $required = true) {
		parent::__construct($name, $required);
		$this->class = $class;
	}
	
	public function getType() {
		return $this->class;
	}
	
	function getClass() {
		return $this->class;
	}
	
	public function encode($value) {
		if(!is_a($value, $this->class)){
			throw new ValidationException("input value on parameter: [{$this->getName()}] is not of type: [{$this->class}]");
		}
		return parent::encode($value);
	}
}