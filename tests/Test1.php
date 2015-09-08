<?php

use storm\actions\Action;
use storm\actions\ClassCheckNode;
use storm\actions\ConditionalValidationNode;
use storm\actions\Engine;
use storm\actions\IntentPayload;
use storm\actions\ItteratorNode;
use storm\actions\ObjectExposerNode;
use storm\actions\ValidationException;
use storm\actions\ValidationProfile;
use storm\actions\ValidationProfileNode;

require_once "autoload.php";
/**
 * @author Dylan Vorster
 */
class Test1 extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var TestValidationProfileHandler 
	 */
	protected static $profileHandler;
	/**
	 *
	 * @var TestIntentHandler
	 */
	protected static $intentHandler;
	
	protected function setUp() {
		if(self::$profileHandler === NULL){
			self::$profileHandler = new TestValidationProfileHandler();
			self::$intentHandler = new TestIntentHandler();
			Engine::get()->addValidationProfileHandler(self::$profileHandler);
			Engine::get()->addIntentHandler(self::$intentHandler);
		}
	}
	
	public function testProfile1(){
		$entryNode = (new ClassCheckNode(TestObject2::class))
			->addNode((new ObjectExposerNode("getName"))
				->addNode((new ConditionalValidationNode(ConditionalValidationNode::TYPE_EQUALS,"Test 1"))
		));
		$profile = new ValidationProfile($entryNode);
		$profile->validate(new TestObject2("Test 1"));
		self::$profileHandler->addProfile($profile);
		return $profile;
	}
	
	/**
	 * @depends testProfile1
	 */
	public function testProfile2(ValidationProfile $testProfile){
		TestIntentHandler::$checkParams = false;
		$entryNode = (new ClassCheckNode(TestObject1::class))
			->addNode((new ObjectExposerNode("getAge"))
				->addNode(new ConditionalValidationNode(ConditionalValidationNode::TYPE_EQUALS,22)))
			->addNode((new ObjectExposerNode("getNestedObjects")) //TestObject2[]
				->addNode((new ItteratorNode()) //TestObject2
					->addNode(new ValidationProfileNode($testProfile)
		)));
		$profile = new ValidationProfile($entryNode);
		$profile->validate(new TestObject1("Test 1",22));
		TestIntentHandler::$checkParams = true;
		return $profile;
	}
	
	/**
	 * @depends testProfile2
	 */
	public function testSerialization(ValidationProfile $profile){
		$encode = json_encode($profile->serialize(),JSON_PRETTY_PRINT);
		$newProfile = ValidationProfile::deserializeProfile(json_decode($encode, true));
		$newProfile->validate(new TestObject1("Test 1", 22));
	}
	
	public function testAction(){
		TestIntentHandler::$checkParams = false;
		$action = new TestAction1();
		$payload = new IntentPayload(['amount' => 4]);
		try{
			$action->validate($payload);
			$this->assertTrue(false, "Expected exception here because missing parameter");
		}  catch (ValidationException $ex){
			$this->assertTrue(true);
		}
		$payload = new IntentPayload(['amount' => 4,'object1' => new TestObject1("Test 1", 22)]);
		$action->validate($payload);
		$action->doAction($payload);
		$this->assertEquals(4,$payload->getOutVariables()['amount']);
		TestIntentHandler::$checkParams = true;
		return $action;
	}
	
	/**
	 * @depends testAction
	 * @depends testProfile2
	 */
	public function testActionParameterValidation(Action $action,  ValidationProfile $profile){
		
		self::$profileHandler->addIntentProfile($action, $action->getParameter('object1'),$profile);
		
		$payload	= new IntentPayload(['amount' => 4,'object1' => new TestObject1("Test 1", 22)]);
		$payload2	= new IntentPayload(['amount' => 4,'object1' => new TestObject1("Test 2", 22)]); //expected to fail
		$payload3	= new IntentPayload(['amount' => 4,'object1' => new TestObject1("Test 1", 23)]); //expected to fail
		
		$this->assertTrue($action->validate($payload));
		$this->assertFalse($action->validate($payload2,false));
		$this->assertFalse($action->validate($payload3,false));
	}
	
}
