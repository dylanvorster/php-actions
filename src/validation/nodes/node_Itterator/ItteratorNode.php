<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ItteratorNode extends ValidationContainerNode{
	
	public function match($value) {
		if(!is_array($value)){
			throw new ValidationException("Value is not an array");
		}
		$exceptions = [];
		foreach ($value as $v) {
			try{
				$this->validateChildren($v);
				return true;
			}catch(ValidationException $ex){
				$exceptions[] = $ex;
			}
		}
		if(count($exceptions) !== 0){
			throw new ValidationException("There was not at least one child in the itterator node which passed the next node validation test");
		}
		return true;
	}
}