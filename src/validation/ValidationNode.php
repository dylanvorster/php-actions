<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class ValidationNode{
	
	
	public function __construct() {
		$this->allow = true;
	}
	
	public function validate($value){
		$this->match($value);
		return true;
	}
	
	protected abstract function match($value);
	
	/**
	 * 
	 * @param array $data
	 * @return ValidationNode
	 */
	public static function deserializeNode($data){
		$node = Engine::get()->getValidationNodeFactory($data['type'])->generate();
		$node->deserialize($data);
		return $node;
	}
	
	public static function serializeNode(ValidationNode $node){
		return $node->serialize();
	}
	
	public function deserialize($data){
	}
	
	public function serialize(){
		return [
			"type" => Engine::get()->getValidationNodeFactoryForClass(get_class($this))->getName(),
		];
	}
	
}