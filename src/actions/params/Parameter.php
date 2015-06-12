<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class Parameter{
	
	/**
	 *
	 * @var Meta
	 */
	protected $meta;
	protected $required;

	public function __construct($name, $required = true) {
		$this->meta = new Meta();
		$this->meta->setName($name);
		$this->required = $required;
	}

	public function getName() {
		return $this->meta->getName();
	}
	
	public function isRequired(){
		return $this->required;
	}
	
	public function encode($value){
		return $value;
	}
	
	public function decode($value){
		return $value;
	}
}