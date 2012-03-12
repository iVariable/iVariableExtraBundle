<?php
namespace iVariable\ExtraBundle\Service\Repository;

class CreationalRepositoryDecorator
{

	private $name = null;
	private $repo = null;
	private $creator = null;

	public function __construct( $creator, $name, $repo ){
		$this->creator = $creator;
		$this->name = $name;
		$this->repo = $repo;
	}

	public function __call( $method, $args ){
		return call_user_func_array( array( $this->repo, $method ) , $args);
	}

	public function newEntity(){
		$args = func_get_args();
		return $this->creator->newEntity( $this->name, $args );
	}

}