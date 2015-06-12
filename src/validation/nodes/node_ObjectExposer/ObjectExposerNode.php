<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ObjectExposerNode extends ValidationContainerNode{
	
	protected $methodName;
	
	public function __construct($methodNode) {
		parent::__construct();
		$this->methodName = $methodNode;
	}

	public function match($value) {
		
		if(!is_object($value)){
			throw new ValidationException("Value is not an object");
		}
		$response = $value->{$this->methodName}();
		$this->validateChildren($response);
		return true;
	}
	
	public function serialize() {
		$response = parent::serialize();
		$response['method'] = $this->methodName;
		return $response;
	}
	
	public function deserialize($data) {
		parent::deserialize($data);
		$this->methodName = $data['method'];
	}

}