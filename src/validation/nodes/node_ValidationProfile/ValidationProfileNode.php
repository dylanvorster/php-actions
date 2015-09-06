<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class ValidationProfileNode extends ValidationNode{

	/**
	 *
	 * @var ValidationProfile
	 */
	protected $profile;
	
	public function __construct(ValidationProfile $profile = NULL) {
		parent::__construct();
		$this->profile = $profile;
	}
	
	protected function match($value) {
		if($this->profile === NULL){
			throw new ValidationException("Validation Profile Node must have a valid Profile, current profile is empty");
		}
		$this->profile->getEntry()->validate($value);
	}
	
	public function serialize() {
		$response = parent::serialize();
		$response['external'] = $this->profile->getIdentifier();
		return $response;
	}
	
	public function deserialize($data) {
		parent::deserialize($data);
		$this->profile = Engine::get()->getValidationProfile($data['external']);
	}
}