<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersStartCommand extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('resque:workers:start')
            ->setDescription('Start resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
Start resque task workers
EOF
        );
    }
    
    
    /**
     *
     * @return \stdClass
     */
    private function getWorkerSets()
    {
        return $this->getApplication()->getConfiguration()->{'resque-workers'}->sets;
    }
    
    
    /**
     *
     * @return array
     */
    private function getWorkerSetNames() {
        $workerSetNames = array();
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {
            $workerSetNames[] = $name;
        }
        
        return $workerSetNames;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInput($input);
        $this->setOutput($output);
        
        if (!$this->isWorkerSetOptionValid()) {
            $output->writeln('workset "'.$this->getWorkersetOption().'" not valid, check app/config.json for valid options');
            return false;
        }
        
        $output->setDecorated(true);
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {            
            if (!$this->hasWorkerSetOption() || ($this->hasWorkerSetOption() && $name == $this->getWorkersetOption())) {
                $this->getOutput()->writeln('Starting workers for: ' . $name);

                $command = 'cd ' . $workerSetDetails->path . ' && ' . $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$workerSetDetails->type};
                $this->getOutput()->writeln('Running command: ' . $command);
                exec($command . ' 2>&1 &');     
            }
        }
    }    
    
    /**
     *
     * @return string|null
     */
    private function getWorkersetOption()
    {
        return $this->getInput()->getOption('workerset');
    }
    
    
    /**
     *
     * @return boolean 
     */
    private function isWorkerSetOptionValid() {
        if (!$this->hasWorkerSetOption()) {
            return true;
        }
        
        return in_array($this->getWorkersetOption(), $this->getWorkerSetNames());
    }
    
    
    /**
     *
     * @return boolean 
     */
    private function hasWorkerSetOption() {
        return !is_null($this->getWorkersetOption());
    }
}