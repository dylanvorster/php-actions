<?php

use storm\actions\Action;
use storm\actions\IntentPayload;
use storm\actions\ObjectParameter;

class TestAction1 extends Action{
	
	public function __construct() {
		parent::__construct("Test Action 1");
		
		$this->addParameter(new ObjectParameter('object1', TestObject1::class));
		$this->inParam('amount');
		$this->outParam('amount');
	}
	
	public function doAction(IntentPayload $payload) {
		$payload->set("amount", $payload->get('amount'));
	}

}