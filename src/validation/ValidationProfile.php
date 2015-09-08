<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ValidationProfile{
	
	protected $entry;
	/**
	 *
	 * @var Meta
	 */
	protected $meta;

	public function __construct(ValidationNode $node = NULL) {
		$this->entry = $node;
		$this->meta = new Meta();
	}
	
	public function getIdentifier() {
		return $this->meta->getIdentifier();
	}

	public function setIdentifier($identifier) {
		$this->meta->setIdentifier($identifier);
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
		$profile = new ValidationProfile(null);
		$profile->deserialize($data);
		return $profile;
	}
	
	public function serialize(){
		$serial = $this->entry->serialize();
		$serial['meta'] =  $this->meta->serialize();
		return $serial;
	}
	
	public function deserialize($data){
		$this->entry = ValidationNode::deserializeNode($data);
		$this->meta->deserialize($data['meta']);
	}
	
}