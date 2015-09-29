<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class Action extends Intent{
	

	/**
	 * Override this to represent what this action will do
	 */
	public abstract function doAction(IntentPayload $payload);
	
	public function invoke(IntentPayload $payload,$throw = true){
		$response = $this->validate($payload,$throw);
		
		//if we are not going to throw an exception, then we must at least return false
		if(!$throw && $response === false){
			return false;
		}
		$this->doAction($payload);
		return true;
	}
	
	/**
	 * 
	 * @param type $array
	 * @param type $throw
	 * @return \storm\actions\IntentPayload
	 */
	public static function simpleInvoke($array = [],$throw = true){
		$action = new static();
		$payload = new IntentPayload($array);
		$action->invoke($payload,$throw);
		return $payload;
	}
}