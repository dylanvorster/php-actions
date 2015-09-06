<?php namespace storm\actions;
/**
 * An array Parameter represents an array of input values as a single parameter.
 * It essentially models any existing parameter, but it encodes the values into
 * sets while maintaining the original key, but with the new encoded value
 * 
 * @author Dylan Vorster
 */
class ArrayParameter extends Parameter{
	
	protected $parameter;

	public function __construct(Parameter $parameter) {
		parent::__construct($parameter->getName(), $parameter->isRequired());
		$this->parameter = $parameter;
	}
	
	public function getType() {
		return $this->parameter->getType().'[]';
	}
	
	public function encode($value) {
		$temp = [];
		foreach ($value as $k => $v) {
			$temp[$k] = $this->parameter->encode($v);
		}
		return $temp;
	}
}