<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractResqueWorkersCommand extends AbstractCommand
{
    /**
     *
     * @return \stdClass
     */
    protected function getWorkerSets()
    {
        return $this->getApplication()->getConfiguration()->{'resque-workers'}->sets;
    }
    
    
    /**
     *
     * @return array
     */
    protected function getWorkerSetNames() {
        $workerSetNames = array();
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {
            $workerSetNames[] = $name;
        }
        
        return $workerSetNames;
    }    
    
    /**
     *
     * @return string|null
     */
    protected function getWorkersetOption()
    {
        return $this->getInput()->getOption('workerset');
    }
    
    
    /**
     *
     * @return boolean 
     */
    protected function isWorkerSetOptionValid() {
        if (!$this->hasWorkerSetOption()) {
            return true;
        }
        
        return in_array($this->getWorkersetOption(), $this->getWorkerSetNames());
    }
    
    
    /**
     *
     * @return boolean 
     */
    protected function hasWorkerSetOption() {
        return !is_null($this->getWorkersetOption());
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInput($input);
        $this->setOutput($output);
        
        if (!$this->isWorkerSetOptionValid()) {
            $output->writeln('workset "'.$this->getWorkersetOption().'" not valid, check app/config.json for valid options');
            return false;
        }
        
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {            
            if (!$this->hasWorkerSetOption() || ($this->hasWorkerSetOption() && $name == $this->getWorkersetOption())) {
                $this->executeForWorkerSet($name, $workerSetDetails);
            }
        }
        
        return;
    }    
    
    abstract protected function executeForWorkerset($name, $workerSetDetails);   
    
    /**
     * Get the start command for a given worker type
     * 
     * @param string $type
     * @return string
     */
    protected function getStartCommand($name, $type) {
        return str_replace('{name}', $name, $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$type}->command);
    }  
    
    /**
     * Get the process Ids for workers based on the command used to start them
     * 
     * @param string $workerStartCommand
     * @return array 
     */
    protected function getWorkerProcessIds($workerStartCommand)
    {               
        $processIdCommand = "ps -ef | grep \"".preg_quote($workerStartCommand)."\" | grep -v grep | awk '{print $2}'";        
        echo $processIdCommand . "\n";
        $commandOutput = array();
        exec($processIdCommand, $commandOutput);
        
        return $commandOutput;
    }    
}