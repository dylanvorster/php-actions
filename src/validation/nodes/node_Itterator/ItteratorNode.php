<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ItteratorNode extends ValidationContainerNode{
	
	protected $matchAll;
	
	public function __construct() {
		parent::__construct();
		$this->matchAll = false;
	}

	public function match($value) {
		
		if(!is_array($value)){
			throw new ValidationException("Value is not an array");
		}
		$exceptions = [];
		
		foreach ($value as $v) {
			try{
				$this->validateChildren($v);
				
				//if we only need one, them simply pass the test
				if(!$this->matchAll){
					return true;
				}
			}catch(ValidationException $ex){
				$exceptions[] = $ex;
			}
		}
		if(count($exceptions) !== 0){
			throw new ValidationException("There was not at least one child in the itterator node which passed the next node validation test");
		}
		return true;
	}
	
	public function deserialize($data) {
		parent::deserialize($data);
		$this->matchAll = $data['itteratorType'] === 'ALL';
	}
	
	public function serialize() {
		$temp = parent::serialize();
		$temp['itteratorType'] = $this->matchAll?'ALL':'ONE';
		return $temp;
	}
}