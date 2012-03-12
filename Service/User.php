<?php
/**
 * Тупо геттер для юзера
 */
namespace iVariable\ExtraBundle\Service;

class User
{

	/**
	 *
	 * @var TIM\TIMBundle\Entity\User
	 */
	protected $user;

	public function __construct( $context ){
		$this->user = $context->getToken()->getUser();
	}

	public function _get(){
		return $this->user;
	}

	public function __call( $method, $args ){
		if( method_exists( $this->user, $method) ){
			return call_user_func_array(array( $this->user, $method ), $args);
		}
		throw new \BadMethodCallException();
	}
}