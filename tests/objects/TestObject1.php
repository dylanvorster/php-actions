<?php

class TestObject1{
	
	protected $age;
	protected $nestedObjects;
	
	function __construct($name, $age) {
		$this->age = $age;
		$this->nestedObjects = [
			new TestObject2($name),
			new TestObject2($name),
			new TestObject2($name)
		];
	}

	function getAge() {
		return $this->age;
	}

	function getNestedObjects() {
		return $this->nestedObjects;
	}
}