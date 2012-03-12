<?php
/**
 * Сервис для получения репозиториев
 */
namespace iVariable\ExtraBundle\Service\Repository;

class Repository
{

	private $refMap = array();

	private $repositoryMap = array();
	private $em;

	public function __construct( $em, $repositoryMap ){
		$this->repositoryMap = $repositoryMap;
		$this->em = $em;
		$result = $this;
		return $result;
	}

	/**
	 * Factory method for instanciating new repo services
	 * @param type $repositoryName
	 * @return Repository\CreationalRepositoryDecorator
	 */
	public function get( $repositoryName ){
		$repo = $this->em->getRepository(
			$this->getRepositoryName( $repositoryName )
		);

		$decoratedRepo = new \iVariable\ExtraBundle\Service\Repository\CreationalRepositoryDecorator( $this, $repositoryName, $repo );

		return $decoratedRepo;
	}

	public function newEntity(){

		$args = func_get_args();
		$name = array_shift($args);

		if( !isset( $this->refMap[$name] ) ){
			$meta = $this->em->getClassMetaData($this->getRepositoryName( $name ) );
			$this->refMap[$name] = new \ReflectionClass( $meta->name );
		}

		$class = $this->refMap[$name];
		
		if( $class->getConstructor() === null ){
			$result = $class->newInstance();
		}else{
			$result = $class->newInstance( $args );
		}

		return $result;
	}

	public function getRepositoryName( $name ){
		return $this->repositoryMap[$name];
	}
}