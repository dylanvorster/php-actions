<?php

use storm\actions\Engine;
use storm\actions\Intent;
use storm\actions\IntentHandler;

class TestIntentHandler extends IntentHandler{
	
	public static $checkParams = true;
	
	public function isIntentAllowed(Intent $i) {
		return true;
	}

	public function shouldParametersBeValidated(Intent $i) {
		return self::$checkParams;
	}

}