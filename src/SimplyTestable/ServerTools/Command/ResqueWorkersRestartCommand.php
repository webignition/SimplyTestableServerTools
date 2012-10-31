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
            ->setDescription('Stop then start resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
Restart resque task workers
EOF
        );
    }
    
    
    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Restarting workers for: ' . $name);

        exec('cd /home/simplytestable/www/tools && php app/console resque:workers:stop --workerset '.$name.' > /dev/null');
        sleep(5);
        exec('cd /home/simplytestable/www/tools && php app/console resque:workers:start --workerset '.$name.' > /dev/null');        
            
        return;
    }

}