<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersStartCommand extends AbstractResqueWorkersCommand
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

    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Starting workers for: ' . $name);                
        $this->executeCommandAtPath(
            $workerSetDetails->path,
            $this->getStartCommand($name, $workerSetDetails->type). ' > ' . $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$workerSetDetails->type}->logpath
        );         
    }
    
    

    
}