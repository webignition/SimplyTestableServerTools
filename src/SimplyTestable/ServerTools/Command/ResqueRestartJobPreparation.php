<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueRestartJobPreparation extends AbstractCommand
{
    
    protected function configure()
    {
        $this
            ->setName('resque:restart-job-preparation')
            ->setDescription('Restart job preparation resque worker if stuck')
            ->setHelp(<<<EOF
Restart job preparation resque worker if stuck
EOF
        );
    }
    
    
    protected function execute() {
        $jobPreparationQueueLogName = '/home/simplytestable/www/app.simplytestable.com/app/logs/resque-jobs.log';
        
        $jobPreparationQueueLogSizeBefore = filesize($jobPreparationQueueLogName);
        
        sleep(10);
        
        $jobPreparationQueueLogSizeAfter = filesize($jobPreparationQueueLogName);
        
        if ($jobPreparationQueueLogSizeAfter - $jobPreparationQueueLogSizeBefore == 0) {
            passthru('cd /home/simplytestable/www/tools && php app/console resque:workers:stop --workerset app-job-prepare');
            sleep(5);
            passthru('cd /home/simplytestable/www/tools && php app/console resque:workers:start --workerset app-job-prepare');
        }
    }
}