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
		$this->validate($payload,$throw);
		$this->doAction($payload);
	}
	
	/**
	 * 
	 * @param type $array
	 * @param type $throw
	 * @return \storm\actions\IntentPayload
	 */
	public static function simpleInvoke($array = [],$throw = true){
		$action = new static();
		$payload = new IntentPayload($action, $array);
		$action->invoke($payload,$throw);
		return $payload;
	}
}