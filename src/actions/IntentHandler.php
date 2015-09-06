<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class IntentHandler{
	
	public abstract function isIntentAllowed(Intent $i);
	
	public abstract function shouldParametersBeValidated(Intent $i);
	
}