<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersRestartCommand extends AbstractResqueWorkersCommand
{
    
    protected function configure()
    {
        $this
            ->setName('resque:workers:restart')
            ->setDescription('Stop and then start again resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
Stop and then start again resque task workers
EOF
        );
    }

    protected function executeForWorkerset($name, $workerSetDetails) {        
        $commandActions = array('stop', 'start');
        foreach ($commandActions as $commandAction) {
            $command = 'php app/console resque:workers:'.$commandAction;
            if ($this->hasWorkerSetOption()) {
                $command .= ' --workset ' . $this->getWorkersetOption();
            }
            
            $commandOutput = array();
            exec($command . ' 2>&1 &', $commandOutput);
            
            foreach ($commandOutput as $outputLine) {
                echo $outputLine . "\n";
            }            
        }
        
        return;
    }  
    

    
}