<?php

namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand {
    
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
}