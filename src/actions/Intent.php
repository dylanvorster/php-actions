<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class Intent{
	
	/**
	 * @var Parameter[]
	 */
	protected $inputParameters;
	/**
	 *
	 * @var Meta
	 */
	protected $meta;

	public function __construct($name) {
		$this->inputParameters = [];
		$this->meta = new Meta();
		$this->meta->setName($name);
	}
	
	/**
	 * 
	 * @return Meta
	 */
	public function getMeta() {
		return $this->meta;
	}

	public function getName() {
		return $this->meta->getName();
	}
	
	//!--------- SHORTHANDS ----------
	
	public function inParam($name){
		$this->addParameter(new Parameter($name));
	}

	
	public function outParam($name){
		$this->addParameter(new Parameter($name),false);
	}
	
	//!-------------------------------
	
	public function removeParameter(Parameter $parameter,$in = true){
		if($in){
			unset($this->inputParameters[$parameter->getName()]);
		}else{
			unset($this->outputParameters[$parameter->getName()]);
		}
	}

	public function addParameter(Parameter $parameter,$in = true){
		if($in){
			$this->inputParameters[$parameter->getName()] = $parameter;
		}else{
			$this->outputParameters[$parameter->getName()] = $parameter;
		}
	}
	
	/**
	 * Validates that we can actually use this action
	 * @param IntentPayload $payload
	 */
	public function validate(IntentPayload $payload,$throw = true){
		//for each parameter we have, we must find the value in the payload
		foreach ($this->inputParameters as $parameter) {
			
			//now we need to find validators for the nodes
			$profiles = Engine::get()->getValidationProfilesForIntentParameter($this, $parameter);
			
			//check to see if the parameter exists
			if(!$payload->parameterExists($parameter->getName())){
				if(!$throw){
					return false;
				}
				throw new ValidationException("Rights check failed for action: [{$this->getName()}] on parameter: [{$parameter->getName()}] ".
					"because the input variable was not found");
			}
			
			//validate each profile which contains an entry node
			foreach ($profiles as $profile) {
				try{
					$result = $profile->validate($payload->get($parameter->getName()));
				}catch(ValidationException $ex){
					if(!$throw){
						return false;
					}
					throw new ValidationException("Rights check failed for action: [{$this->getName()}] on parameter: [{$parameter->getName()}] reason: [{$ex->getMessage()}]");
				}
				
				//and use the value from the payload (if ever a payload returns false, then we know something went wrong, but always prefer exceptions)
				if($result === false){
					if(!$throw){
						return false;
					}
					throw new ValidationException("Rights check failed for action: [{$this->getName()}] on parameter: [{$parameter->getName()}]");
				}
			}
		}
		return true;
	}
	
	/**
	 * Get all the parameters as an array with the key = the name of the parameter
	 * @return Parameter[]
	 */
	function getParameters() {
		return $this->inputParameters;
	}
	
	/**
	 * Get a specific parameter from this action based on the name provided.
	 * 
	 * @param string $name the name of the parameter
	 * @return Parameter|ObjectParameter
	 * @throws ValidationException
	 */
	public function getParameter($name){
		if(!isset($this->inputParameters[$name])){
			throw new ValidationException("Could not find parameter: {$name}");
		}
		return $this->inputParameters[$name];
	}
}