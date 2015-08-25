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
	
	protected function setUp() {
		if(self::$profileHandler === NULL){
			self::$profileHandler = new TestValidationProfileHandler();
			Engine::get()->addValidationProfileHandler(self::$profileHandler);
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
		$entryNode = (new ClassCheckNode(TestObject1::class))
			->addNode((new ObjectExposerNode("getAge"))
				->addNode(new ConditionalValidationNode(ConditionalValidationNode::TYPE_EQUALS,22)))
			->addNode((new ObjectExposerNode("getNestedObjects")) //TestObject2[]
				->addNode((new ItteratorNode()) //TestObject2
					->addNode(new ValidationProfileNode($testProfile)
		)));
		$profile = new ValidationProfile($entryNode);
		$profile->validate(new TestObject1("Test 1",22));
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
		$action = new TestAction1();
		$payload = new IntentPayload($action, ['amount' => 4]);
		try{
			$action->validate($payload);
			$this->assertTrue(false, "Expected exception here because missing parameter");
		}  catch (ValidationException $ex){
			$this->assertTrue(true);
		}
		$payload = new IntentPayload($action, ['amount' => 4,'object1' => new TestObject1("Test 1", 22)]);
		$action->validate($payload);
		$action->doAction($payload);
		$this->assertEquals(4,$payload->getOutVariables()['amount']);
		return $action;
	}
	
	/**
	 * @depends testAction
	 * @depends testProfile2
	 */
	public function testActionParameterValidation(Action $action,  ValidationProfile $profile){
		
		self::$profileHandler->addIntentProfile($action, $action->getParameter('object1'),$profile);
		
		$payload	= new IntentPayload($action, ['amount' => 4,'object1' => new TestObject1("Test 1", 22)]);
		$payload2	= new IntentPayload($action, ['amount' => 4,'object1' => new TestObject1("Test 2", 22)]);
		$payload3	= new IntentPayload($action, ['amount' => 4,'object1' => new TestObject1("Test 1", 23)]);
		$action->validate($payload);
		$this->assertTrue(true);
		
		try{
			$action->validate($payload2);
			$this->assertTrue(false,"Expected it to fail here because the payload has the wrong value: Test 2");
		} catch (ValidationException $ex) {
			$this->assertTrue(true);
		}
		
		try{
			$action->validate($payload3);
			$this->assertTrue(false,"Expected it to fail here because the payload has the wrong value: 23");
		} catch (ValidationException $ex) {
			$this->assertTrue(true);
		}
	}
	
}
