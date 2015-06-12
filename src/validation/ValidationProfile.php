<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ValidationProfile{
	
	protected $entry;
	protected $meta;
	protected $identifier;

	public function __construct(ValidationNode $node) {
		$this->entry = $node;
		$this->meta = new Meta();
		$this->identifier = sha1(uniqid());
	}
	
	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * 
	 * @return ValidationNode
	 */
	function getEntry() {
		return $this->entry;
	}
	
	function validate($value){
		return $this->entry->validate($value);
	}
	
	/**
	 * 
	 * @return Meta
	 */
	function getMeta() {
		return $this->meta;
	}

	/**
	 * 
	 * @param type $data
	 * @return \storm\actions\ValidationProfile
	 */
	public static function deserializeProfile($data){
		$node = ValidationNode::deserializeNode($data['entry']);
		$profile = new ValidationProfile($node);
		return $profile;
	}
	
	public function serialize(){
		return [
			'entry' => $this->entry->serialize(),
			'meta' => $this->meta->serialize(),
		];
	}
	
}