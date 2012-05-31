<?php
namespace iVariable\ExtraBundle\Monolog\Handler;

class FlushingStreamHandler extends \Monolog\Handler\StreamHandler
{

	protected $flushHitCounter = 0;
	protected $flushOnWriteCount = 5;

	public function __construct( $stream, $level = Logger::DEBUG, $bubble = true ){
		parent::__construct( $stream, $level, $bubble );
	}

	public function setFlushOnWriteCount( $count ){
		$this->flushOnWriteCount = (int)$count;
	}

    protected function write(array $record)
    {
		parent::write($record);
		if( $this->flushOnWriteCount <= $this->flushHitCounter++ ){
			$this->flushHitCounter = 0;
			fflush($this->stream);
		}
    }
}
