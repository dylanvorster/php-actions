<?php

use storm\actions\Intent;
use storm\actions\Parameter;
use storm\actions\ValidationProfile;
use storm\actions\validationProfileHandler;

class TestValidationProfileHandler extends validationProfileHandler{
	
	protected $profiles;
	protected $attachedProfiles;


	public function __construct() {
		$this->profiles = [];
		$this->attachedProfiles = [];
	}
	
	public function addProfile(ValidationProfile $profile){
		$this->profiles[$profile->getIdentifier()] = $profile;
	}

	public function getValidationProfile($identifier) {
		return $this->profiles[$identifier];
	}
	
	public function addIntentProfile(Intent $intent, Parameter $parameter,  ValidationProfile $profile){
		$this->attachedProfiles[$intent->getName().' '.$parameter->getName()] = $profile;
	}

	public function getValidationProfilesFor(Intent $action,Parameter $parameter) {
		if(isset($this->attachedProfiles[$action->getName().' '.$parameter->getName()])){
			return $this->attachedProfiles[$action->getName().' '.$parameter->getName()];
		}
		return [];
	}

}