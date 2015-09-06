<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class Engine{
	
	/**
	 *
	 * @var ValidationNodeFactory[]
	 */
	protected $validationNodeFactories;
	/**
	 *
	 * @var validationProfileHandler[]
	 */
	protected $validationProfileHandlers;
	
	/**
	 *
	 * @var IntentHandler[]
	 */
	protected $intentHandlers;
	
	
	/**
	 *
	 * @var static
	 */
	protected static $static;

	public function __construct() {
		$this->validationProfileHandlers = [];
		$this->validationNodeFactories = [];
		$this->intentHandlers = [];
		
		//install defaults
		$this->addValidationNodeFactory(new ClassCheckNodeFactory());
		$this->addValidationNodeFactory(new ConditionalValidationNodeFactory());
		$this->addValidationNodeFactory(new ItteratorNodeFactory());
		$this->addValidationNodeFactory(new ObjectExposerNodeFactory());
		$this->addValidationNodeFactory(new ValidationProfileNodeFactory());
	}
	
	public function addIntentHandler(IntentHandler $handler){
		$this->intentHandlers[] = $handler;
	}
	
	public function addValidationProfileHandler(validationProfileHandler $handler){
		$this->validationProfileHandlers[] = $handler;
	}
	
	/**
	 * checks to see if parameters should be validated, we only need
	 * on of the handlers to say yes
	 * 
	 * @param \storm\actions\Intent $intent
	 * @return boolean
	 */
	public function shouldParametersBeValidated(Intent $intent){
		foreach ($this->intentHandlers as $handler) {
			if($handler->shouldParametersBeValidated($intent)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Checks to see if an intent is allowed for the given session,
	 * we only need one to say allowed, to pass this test
	 * 
	 * @param \storm\actions\Intent $intent
	 * @return boolean
	 */
	public function isIntentAllowed(Intent $intent){
		foreach ($this->intentHandlers as $handler) {
			if($handler->isIntentAllowed($intent)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 
	 * @param \storm\actions\Intent $intent
	 * @param \storm\actions\Parameter $param
	 * @return \storm\actions\ValidationProfile[]
	 * @throws ValidationException
	 */
	public function getValidationProfilesForIntentParameter(Intent $intent,  Parameter $param){
		$profiles = [];
		foreach ($this->validationProfileHandlers as $handler) {
			$response = $handler->getValidationProfilesFor($intent,$param);
			if(is_array($response)){
				$profiles = array_merge($profiles,$response);
			}else if(is_object($response) && $response instanceof ValidationProfile){
				$profiles[] = $response;
			}
		}
		return $profiles;
	}
	
	/**
	 * 
	 * @param type $identifier
	 * @return \storm\actions\ValidationProfile
	 * @throws ValidationException
	 */
	public function getValidationProfile($identifier){
		foreach ($this->validationProfileHandlers as $handler) {
			$response = $handler->getValidationProfile($identifier);
			if($response instanceof ValidationProfile){
				return $response;
			}
		}
		throw new ValidationException("Unable to retrieve validation profile for identifier: [{$identifier}]");
	}
	
	/**
	 * 
	 * @param type $name
	 * @return ValidationNodeFactory
	 * @throws ValidationException
	 */
	public function getValidationNodeFactory($name){
		if(isset($this->validationNodeFactories[$name])){
			return $this->validationNodeFactories[$name];
		}
		throw new ValidationException("Cant find validation factory for: [{$name}]");
	}
	
	/**
	 * 
	 * @param type $class
	 * @return ValidationNodeFactory
	 * @throws ValidationException
	 */
	public function getValidationNodeFactoryForClass($class){
		foreach ($this->validationNodeFactories as $factory) {
			if($factory->getClass() === $class){
				return $factory;
			}
		}
		throw new ValidationException("Cant find ValidationNodeFactory for class: [{$class}]");
	}
	
	public function addValidationNodeFactory(ValidationNodeFactory $factory){
		$this->validationNodeFactories[$factory->getName()] = $factory;
	}
	
	/**
	 * 
	 * @return static
	 */
	public static function get(){
		if(self::$static === NULL){
			self::$static = new Engine();
		}
		return self::$static;
	}
	
}