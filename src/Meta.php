<?php namespace storm\actions;
/**
 * @author Dylan Vorster
 */
class Meta{
	
	protected $name;
	protected $description;
	protected $tags;
	protected $authors;
	
	public function __construct() {
		$this->tags = [];
		$this->authors = [];
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
			'name' => $this->name,
			'desc' => $this->description,
			'tags' => implode(', ', $this->tags)
		];
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