<?php

namespace SimplyTestable\ServerTools\Exception;

class ConfigurationException extends \Exception {    
    
    private $expectedPath;    
    
    /**
     * 
     * @param string $message
     * @param int $code
     * @param string $expectedPath
     * @param \Exception $previous
     */
    public function __construct($message, $code, $expectedPath) {
        parent::__construct($message, $code);
        $this->expectedPath = $expectedPath;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getExpectedPath() {
        return $this->expectedPath;
    }
    
}