<?php namespace storm\actions;
/**
 * Helper class for working with STORM React Actions Designer
 * 
 * @author Dylan Vorster
 */
class RightsDesignerBootstrap{
	
	public static function convertValidationProfileToArray(ValidationProfile $profile){
		$array = $profile->serialize();
		
		return $array;
	}
	
	public static function convertIntentToArray(Intent $intent){
		$array = [
			'allowed' => Engine::get()->isIntentAllowed($intent),
			'validateParameters' => Engine::get()->shouldParametersBeValidated($intent),
			"meta" => $intent->getMeta()->serialize(),
			"fields" => []
		];
		
		foreach ($intent->getParameters() as $parameter) {
			$array['fields'][] = [
				'name' => $parameter->getName(),
				'type' => $parameter->getType(),
				'profiles' => array_map(function(ValidationProfile $profile){
					return $profile->getIdentifier();
				}, Engine::get()->getValidationProfilesForIntentParameter($intent, $parameter))
			];
		}
		
		return $array;
	}
	
	public static function collect(Array $intents){
		$intents = array_map(function(Intent $intent){
			return self::convertIntentToArray($intent);
		}, array_values($intents));
		
		$finalArray = [];
		foreach ($intents as $intent) {
			foreach ($intent['fields'] as $field) {
				foreach ($field['profiles'] as $profile) {
					$finalArray[''.$profile] = true;
				}
			}
		}
		return [
			'intents' => $intents,
			'profiles' => array_map(function($identifier){
				return self::convertValidationProfileToArray(Engine::get()->getValidationProfile($identifier));
			}, array_keys($finalArray))
		];
	}
	
}