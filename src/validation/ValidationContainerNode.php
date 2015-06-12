<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
abstract class ValidationContainerNode extends ValidationNode{
	
	protected $nodes;
	
	public function __construct() {
		parent::__construct();
		$this->nodes = [];
	}
	
	public function &addNode(ValidationNode $node){
		$this->nodes[] = $node;
		return $this;
	}
	
	public function validateChildren($value){
		foreach ($this->nodes as $node) {
			$node->validate($value);
		}
		return true;
	}
	
	public function deserialize($data) {
		parent::deserialize($data);
		if($data['nodes']){
			foreach ($data['nodes'] as $node) {
				$this->addNode(self::deserializeNode($node));
			}
		}
	}
	
	public function serialize() {
		$response = parent::serialize();
		if(count($this->nodes) === 0){
			return $response;
		}
		$response['nodes'] = array_map(function(ValidationNode $child){
			return $child->serialize();
		}, $this->nodes);
		return $response;
	}
}