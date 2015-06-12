<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class ValidationNode{
	
	protected $allow;
	
	public function __construct() {
		$this->allow = true;
	}
	
	public function validate($value){
		$this->match($value);
		if(!$this->allow){
			throw new ValidationException("Not allowed to access action with these properties");
		}
		return true;
	}
	
	protected abstract function match($value);
	
	/**
	 * 
	 * @param array $data
	 * @return ValidationNode
	 */
	public static function deserializeNode($data){
		$node = Engine::get()->getValidationNodeFactory($data['name'])->generate();
		$node->deserialize($data);
		return $node;
	}
	
	public static function serializeNode(ValidationNode $node){
		return $node->serialize();
	}
	
	public function deserialize($data){
		$this->allow = $data['allow'];
	}
	
	public function serialize(){
		return [
			'name' => Engine::get()->getValidationNodeFactoryForClass(get_class($this))->getName(),
			'allow' => $this->allow
		];
	}
	
}