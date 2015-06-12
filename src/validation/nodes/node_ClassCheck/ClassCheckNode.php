<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ClassCheckNode extends ValidationContainerNode{
	
	protected $class;
	protected $nodes;

	public function __construct($class = NULL) {
		parent::__construct();
		$this->class = $class;
		$this->nodes = [];
	}
	
	public function match($value) {
		if(!is_object($value)){
			throw new ValidationException("Input value is not an object");
		}
		if(get_class($value) !== $this->class){
			throw new ValidationException("Class does not match: ".$this->class);
		}
		
		$this->validateChildren($value);
		
		return true;
	}
	
	public function deserialize($data) {
		parent::deserialize($data);
		$this->class = $data['class'];
	}
	
	public function serialize() {
		$response = parent::serialize();
		$response['class'] = $this->class;
		return $response;
	}

}