<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class ValidationNodeFactory{
	
	protected $meta;
	protected $name;
	protected $class;
		
	function __construct($name,$class) {
		$this->name = $name;
		$this->class = $class;
		$this->meta = new Meta();
	}
	
	public function getClass() {
		return $this->class;
	}
		
	public function getName() {
		return $this->name;
	}
	
	public function &getMeta() {
		return $this->meta;
	}

	/**
	 * @return ValidationNode Description
	 */
	public abstract function generate();
}