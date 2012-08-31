<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersListCommand extends AbstractResqueWorkersCommand
{
    
    protected function configure()
    {
        $this
            ->setName('resque:workers:list')
            ->setDescription('List resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
List resque task workers
EOF
        );
    }

    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Listing workers for: ' . $name);                
        
        $workerProcessIds = $this->getWorkerProcessIds($this->getStartCommand($name, $workerSetDetails->type));
        
        foreach ($workerProcessIds as $workerProcessId) {
            $commandOutput = array();
            exec('ps hf --pid '.$workerProcessId, $commandOutput);
            
            foreach ($commandOutput as $outputLine) {
                echo $outputLine . "\n";
            }
        }       
    }
    
}