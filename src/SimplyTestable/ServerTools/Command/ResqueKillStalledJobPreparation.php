<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueKillStalledJobPreparation extends AbstractCommand
{
    const DEFAULT_DURATION_THRESHOLD = 300;
    
    
    protected function configure()
    {
        $this
            ->setName('resque:kill-stalled-job-preparation')
            ->setDescription('Kill stalled job preparation tasks')
            ->addOption('durationThreshold', 't', InputOption::VALUE_OPTIONAL, 'Kill job preparation running longer than {durationThreshold} seconds')
            ->setHelp(<<<EOF
Kill stalled job preparation tasks
EOF
        );
    }
    
    
    protected function execute() {
        $this->getProcessIdsForThresholdExceedingJobs();
        // "ps -ef | grep \"".preg_quote($workerStartCommand)."\" | grep -v grep | awk '{print $2}'"
        // "ps -ef | grep \"php app/console\" | grep -v grep | awk '{print $2}'"
        // ps -eo pid,etime,command
        // "ps -eo pid,etime,command | grep \"php app/console\" | grep -v grep | awk '{print $2}'"
        // "ps -eo pid,etime,command | grep "php app/console simplytestable:task:perform" | grep -v grep | awk '{print $2}'"
        
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
    } 
    
    // return $this->getInput()->getOption('workerset');
    
    
    private function getProcessIdsForThresholdExceedingJobs() {
        $jobPrepareProcessIdsAndTimesCommand = "ps -eo pid,etime,command | grep \"php app/console simplytestable:task:perform\" | grep -v grep | awk '{print $1,$2}'";        
        $output = array();
        
        exec($jobPrepareProcessIdsAndTimesCommand, $output);
        
        var_dump($output);
        exit();
    }
    
    
    /**
     * 
     * @return int
     */
    private function getDurationThreshold() {
        $durationThresholdOption = $this->getDurationThresholdOption();
        if (!is_null($durationThresholdOption)) {
            return $durationThresholdOption;
        }
        
        return self::DEFAULT_DURATION_THRESHOLD;
    }
    
    
    /**
     * 
     * @return int
     */
    private function getDurationThresholdOption() {
        return filter_var($this->getInput()->getOption('durationThreshold'), FILTER_VALIDATE_INT, array(
            'options' => array(
                'default' => null,
                'min_range' => 0
            )
        ));        
    }
   

}