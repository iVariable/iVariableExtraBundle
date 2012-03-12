<?php
/**
 * Сервис для получения репозиториев
 */
namespace iVariable\ExtraBundle\Service;

class Repository
{

	private $repositoryMap = array();
	private $em;

	public function __construct( $em, $repositoryMap ){
		$this->repositoryMap = $repositoryMap;
		$this->em = $em;
		$result = $this;
		return $result;
	}

	public function get( $repositoryName ){
		return $this->em->getRepository($this->repositoryMap[$repositoryName]);
	}
}