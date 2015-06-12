<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class Action extends Intent{
	

	/**
	 * Override this to represent what this action will do
	 */
	public abstract function doAction(IntentPayload $payload);
}