<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersStartCommand extends AbstractResqueWorkersCommand
{
    const RETURN_CODE_OK = 0;
    const RETURN_CODE_WORKERS_ALREADY_RUNNING = 1;
    
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
        
        parent::configure();
    }

    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Starting workers for: ' . $name);    
        
        $workerProcessIds = $this->getWorkerProcessIds($this->getStartCommand($name, $workerSetDetails->type));
        if (count($workerProcessIds)) {
            $this->getOutput()->writeln('There are already workers running for: ' . $name);
            $this->getOutput()->writeln('List them with: php app/console resque:workers:list --workerset ' . $name);
            $this->getOutput()->writeln('Stop them with: php app/console resque:workers:list --workerset ' . $name);
            return self::RETURN_CODE_WORKERS_ALREADY_RUNNING;
        }
        
        $this->executeCommandAtPath(
            $workerSetDetails->path,
            $this->getStartCommand($name, $workerSetDetails->type). ' > ' . $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$workerSetDetails->type}->logpath
        );
            
        return self::RETURN_CODE_OK;
    }
    
    

    
}