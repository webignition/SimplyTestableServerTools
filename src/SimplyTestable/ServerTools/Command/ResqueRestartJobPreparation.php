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
    
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $jobPreparationQueueLogName = '/home/simplytestable/www/app.simplytestable.com/app/logs/resque-jobs.log';

        $beforeOutput = array();
        exec('wc -c < ' . $jobPreparationQueueLogName, $beforeOutput);
        $jobPreparationQueueLogSizeBefore = (int)$beforeOutput[0];
        
        sleep(30);

        $afterOutput = array();
        exec('wc -c < ' . $jobPreparationQueueLogName, $afterOutput);
        $jobPreparationQueueLogSizeAfter = (int)$afterOutput[0];
        
        var_dump($beforeOutput, $afterOutput);

        if ($jobPreparationQueueLogSizeAfter - $jobPreparationQueueLogSizeBefore == 0) {
            exec('cd /home/simplytestable/www/tools && php app/console resque:workers:stop --workerset app-job-prepare > /dev/null');
            sleep(5);
            exec('cd /home/simplytestable/www/tools && php app/console resque:workers:start --workerset app-job-prepare > /dev/null');
        }
    }

}