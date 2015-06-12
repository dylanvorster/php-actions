<?php

class TestObject2{
	
	protected $name;
	
	function __construct($name) {
		$this->name = $name;
	}
	
	function getName() {
		return $this->name;
	}
}