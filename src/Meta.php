<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class Meta{
	
	protected $name;
	protected $description;
	protected $tags;
	protected $authors;
	protected $identifier;
	
	public function __construct() {
		$this->tags = [];
		$this->authors = [];
		$this->identifier = sha1(uniqid());
	}
	
	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setTags($tags) {
		$this->tags = $tags;
	}

	public function setAuthors($authors) {
		$this->authors = $authors;
	}
	
	public function serialize(){
		return [
			'guid' => $this->identifier,
			'name' => $this->name,
			'desc' => $this->description,
//			'tags' => implode(', ', $this->tags)
		];
	}
	
	public function deserialize($data){
		$this->identifier = $data['guid'];
		$this->name = $data['name'];
		$this->description = $data['desc'];
//		$this->tags = $data['tags'];
	}
	
	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getTags() {
		return $this->tags;
	}

	public function getAuthors() {
		return $this->authors;
	}
}