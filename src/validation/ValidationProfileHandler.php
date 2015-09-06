<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class ValidationProfileHandler{
	
	public abstract function getValidationProfile($identifier);
	
	public abstract function getValidationProfilesFor(Intent $action,Parameter $parameter);
}