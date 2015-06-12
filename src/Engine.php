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
	protected static $static;

	public function __construct() {
		$this->validationProfileHandlers = [];
		$this->validationNodeFactories = [];
		$this->addValidationNodeFactory(new ClassCheckNodeFactory());
		$this->addValidationNodeFactory(new ConditionalValidationNodeFactory());
		$this->addValidationNodeFactory(new ItteratorNodeFactory());
		$this->addValidationNodeFactory(new ObjectExposerNodeFactory());
		$this->addValidationNodeFactory(new ValidationProfileNodeFactory());
	}
	
	public function addValidationProfileHandler(validationProfileHandler $handler){
		$this->validationProfileHandlers[] = $handler;
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