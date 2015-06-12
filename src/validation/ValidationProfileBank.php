<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ValidationProfileBank{
	
	protected $profiles;
	
	public function __construct() {
		$this->profiles = [];
	}
	
	public function addValidationProfile(ValidationProfile $profile){
		$this->profiles[$profile->getAction()->getName()] = $profile;
	}
	
	/**
	 * Will try and find a validation profile for the action provided
	 * 
	 * @param Action $action
	 * @return ValidationProfile
	 * @throws ValidationException
	 */
	public function getValidationProfile(Action $action){
		if(!isset($this->profiles[$action->getName()])){
			throw new ValidationException("Could not find a validation profile for action: [{$action->getName()}]");
		}
		return $this->profiles[$action->getName()];
	}
}