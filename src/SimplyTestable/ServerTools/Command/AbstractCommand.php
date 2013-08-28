<?php

namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCommand extends BaseCommand {
    
    const DEFAULT_ENVIRONMENT = 'dev';
    
    /**
     *
     * @var InputInterface 
     */
    private $input;
    
    /**
     *
     * @var OutputInterface 
     */
    private $output;
    
    
    /**
     *
     * @param InputInterface $input 
     */
    protected function setInput(InputInterface $input)
    {
        $this->input = $input;
    }
    
    
    /**
     *
     * @param OutputInterface $output 
     */
    protected function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }


    /**
     *
     * @return InputInterface 
     */
    protected function getInput()
    {
        return $this->input;
    }
    
    
    /**
     *
     * @return OutputInterface 
     */
    protected function getOutput()
    {
        return $this->output;
    }
    
    /**
     * Change to a directory and execute a command from there
     * 
     * @param string $path
     * @param string $command 
     */
    protected function executeCommandAtPath($path, $command) {        
        $fullCommand = 'cd ' . $path . ' && ' . $command;                    
        $output = array();
        
        exec($fullCommand . ' 2>&1 &', $output);
    }  
    
    protected function configure()
    {
        $this->addOption('environment', 'e', InputOption::VALUE_OPTIONAL, 'environment to use');
    }  
    
    
    /**
     * 
     * @return string
     */
    protected function getEnvironmentName() {
        $environment = $this->getInput()->getOption('environment');
        return (is_null($environment)) ? self::DEFAULT_ENVIRONMENT : $environment;        
    } 
    
    
    /**
     * 
     * @return \SimplyTestable\ServerTools\Console\Application
     */
    public function getApplication() {
        $application = parent::getApplication();
        $application->setEnvironment($this->getEnvironmentName());
        
        return $application;
    }
}