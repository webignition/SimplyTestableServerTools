<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersStopCommand extends AbstractResqueWorkersCommand
{
    
    protected function configure()
    {
        $this
            ->setName('resque:workers:stop')
            ->setDescription('Stop resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
Stop resque task workers
EOF
        );
        
        parent::configure();        
    }
    
    
    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Stopping workers for: ' . $name);
        
        $workerProcessIds = $this->getWorkerProcessIds($this->getStartCommand($name, $workerSetDetails->type));
        foreach ($workerProcessIds as $workerProcessId) {
            $commandOutput = array();
            exec('kill -9 ' . $workerProcessId, $commandOutput);
        }       
        
        return;
    }
}