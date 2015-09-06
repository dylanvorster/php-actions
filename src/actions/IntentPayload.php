<?php namespace storm\actions;
/**
 * Represents a transport layer that will be passed into actions when they need
 * to be fired or validated. It acts as an interface for an Action to get input and output
 * variables from the event regardless of where the variables came from
 * 
 * @author Dylan Vorster
 */
class IntentPayload{
	
	protected $outVariables;
	protected $inVariables;
	protected $intent;
	
	public function __construct(Intent $intent,$variables) {
		$this->inVariables = $variables;
		$this->outVariables = [];
		$this->intent = $intent;
	}
	
	public function set($name,$value){
		$this->outVariables[$name] = $value;
	}
	
	/**
	 * Get the final value of the payload in its forward-form.
	 * Any variables which represent objects, will become objects,
	 * Any raw variables which are not objects will stay raw
	 * 
	 * @param type $name
	 * @return string|int
	 */
	public function get($name){
		return $this->intent->getParameter($name)->encode($this->inVariables[$name]);
	}
	
	public function parameterExists($name){
		return isset($this->inVariables[$name]);
	}
	
	/**
	 * Alias to the actions validate method
	 */
	public function validate($throw = true){
		return $this->intent->validate($this,$throw);
	}
	
	public function getOutVariables() {
		return $this->outVariables;
	}
}