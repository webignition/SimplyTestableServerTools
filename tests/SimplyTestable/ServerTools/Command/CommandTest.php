<?php

namespace SimplyTestable\ServerTools\Tests\Command;

use Symfony\Component\Console\Input\ArgvInput;
//use Symfony\Component\Console\Application;
use SimplyTestable\ServerTools\Console\Application;

abstract class CommandTest extends \PHPUnit_Framework_TestCase {
    
    
    /**
     *
     * @var Symfony\Bundle\FrameworkBundle\Console\Application
     */
    private $application;
    

    public function setUp() {        
        $this->application = new Application(__DIR__ . '/../../../../app');
        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);
        //$this->runConsole('resque:workers:stop');
    } 
    
    public function tearDown() {
        //$this->runConsole('resque:workers:stop');
    }    
    
    protected function runConsole($command, Array $options = array()) {
        $args = array(
            'app/console',
            $command,
            '-e',
            'test',
            '-n'
        );        
        
        foreach ($options as $key => $value) {
            $args[] = $key;
            
            if (!is_null($value) && !is_bool($value)) {
                $args[] = $value;
            }
        }

        $input = new ArgvInput($args);                 
        
        $output =  new \SimplyTestable\ServerTools\Console\MemoryWriter();
        $returnCode = $this->application->run($input, $output);
        return array(
            'returnCode' => $returnCode,
            'output' => $output
        );
    }
    
    protected function getOutputLines(\Symfony\Component\Console\Output\Output $output) {
        return explode("\n", trim($output->getOutput()));
    }    

}