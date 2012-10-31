<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersRetartCommand extends AbstractResqueWorkersCommand
{
    
    protected function configure()
    {
        $this
            ->setName('resque:workers:restart')
            ->setDescription('Stop then start resque task workers')
            ->addOption('workerset', 'w', InputOption::VALUE_OPTIONAL, 'name of worker set')
            ->setHelp(<<<EOF
Start resque task workers
EOF
        );
    }
    
    
    protected function executeForWorkerset($name, $workerSetDetails) {
        $this->getOutput()->writeln('Restarting workers for: ' . $name);
        
        exec('cd /home/simplytestable/www/tools && php app/console resque:workers:stop --workerset '.$name.' > /dev/null');
        sleep(5);
        exec('cd /home/simplytestable/www/tools && php app/console resque:workers:start --workerset '.$name.' > /dev/null');        
        
//        $workerProcessIds = $this->getWorkerProcessIds($this->getStartCommand($name, $workerSetDetails->type));
//        if (count($workerProcessIds)) {
//            $this->getOutput()->writeln('There are already workers running for: ' . $name);
//            $this->getOutput()->writeln('List them with: php app/console resque:workers:list --workerset ' . $name);
//            $this->getOutput()->writeln('Stop them with: php app/console resque:workers:list --workerset ' . $name);
//            return;
//        }
//        
//        $this->executeCommandAtPath(
//            $workerSetDetails->path,
//            $this->getStartCommand($name, $workerSetDetails->type). ' > ' . $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$workerSetDetails->type}->logpath
//        );
            
        return;
    }    
    
    
//    protected function execute() {
//        $jobPreparationQueueLogName = '/home/simplytestable/www/app.simplytestable.com/app/logs/resque-jobs.log';
//
//        $beforeOutput = array();
//        exec('wc -c < ' . $jobPreparationQueueLogName, $beforeOutput);
//        $jobPreparationQueueLogSizeBefore = (int)$beforeOutput[0];
//        
//        sleep(30);
//
//        $afterOutput = array();
//        exec('wc -c < ' . $jobPreparationQueueLogName, $afterOutput);
//        $jobPreparationQueueLogSizeAfter = (int)$afterOutput[0];
//        
//        var_dump($beforeOutput, $afterOutput);
//
//        if ($jobPreparationQueueLogSizeAfter - $jobPreparationQueueLogSizeBefore == 0) {
//            exec('cd /home/simplytestable/www/tools && php app/console resque:workers:stop --workerset app-job-prepare > /dev/null');
//            sleep(5);
//            exec('cd /home/simplytestable/www/tools && php app/console resque:workers:start --workerset app-job-prepare > /dev/null');
//        }
//    }

}