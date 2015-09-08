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
	
	protected $id;

	public function __construct($name,$id = NULL) {
		$this->inputParameters = [];
		$this->meta = new Meta();
		$this->meta->setName($name);
		if($id == NULL){
			$this->id = $name;
		}
	}
	
	function getID() {
		return $this->id;
	}

	function setID($id) {
		$this->id = $id;
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
		
		$payload->setIntent($this);
		
		//is this intent allowed at all?
		if(!Engine::get()->isIntentAllowed($this)){
			if($throw){
				throw new ValidationException("Rights check failed for intent: [{$this->getName()}]: not allowed to access this intent.");
			}
			return false;
		}
		
		$checksPassed = 0;
		$shouldParametersBeValidated = Engine::get()->shouldParametersBeValidated($this);
		
		if(count($this->inputParameters) == 0){
			return true;
		}
		
		//yes it is allowed, now validate each parameter
		//for each parameter we have, we must find the value in the payload
		foreach ($this->inputParameters as $parameter) {

			//check to see if the parameter exists
			if(!$payload->parameterExists($parameter->getName())){
				if(!$throw){
					return false;
				}
				throw new ValidationException("Rights check failed for intent: [{$this->getName()}] on parameter: [{$parameter->getName()}] ".
					"because the input variable was not found");
			}

			//should we check parameters (useful if the user is an admin in your system, because you can then skip this step)
			if($shouldParametersBeValidated){
				
				//now we need to find validators for the nodes
				$profiles = Engine::get()->getValidationProfilesForIntentParameter($this, $parameter);


				//validate each profile which contains an entry node
				foreach ($profiles as $profile) {
					try{
						$result = $profile->validate($payload->get($parameter->getName()));
					}catch(ValidationException $ex){
						if(!$throw){
							return false;
						}
						throw new ValidationException("Rights check failed for intent: [{$this->getName()}] on parameter: [{$parameter->getName()}] reason: [{$ex->getMessage()}]");
					}

					//and use the value from the payload (if ever a payload returns false, then we know something went wrong, but always prefer exceptions)
					if($result === false){
						if(!$throw){
							return false;
						}
						throw new ValidationException("Rights check failed for intent: [{$this->getName()}] on parameter: [{$parameter->getName()}]");
					}
					$checksPassed++;
				}
			}
		}
			
		//if this happens, it means we didnt find any profiles
		if($checksPassed == 0 && $shouldParametersBeValidated){
			if(!$throw){
				return false;
			}
			throw new ValidationException("Rights check failed for intent: [{$this->getName()}] because there were no profiles setup to test with");
		}
		
		//all checks passed
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