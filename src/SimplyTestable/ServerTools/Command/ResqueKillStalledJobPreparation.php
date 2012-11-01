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
    
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->setInput($input);
        $this->setOutput($output);
        
        $processIdsToKill = $this->getProcessIdsForThresholdExceedingJobs();
        
        foreach ($processIdsToKill as $processIdToKill) {
            exec('kill -9 '.$processIdToKill);
        }
    }
    
    
    private function getProcessIdsForThresholdExceedingJobs() {
        $jobPrepareProcessIdsAndTimesCommand = "ps -eo pid,etime,command | grep \"php app/console simplytestable:job:prepare\" | grep -v grep | awk '{print $1,$2}'";        
        $output = array();
        
        exec($jobPrepareProcessIdsAndTimesCommand, $output);
        
        $processesExeceedingDuration = array();
        
        foreach ($output as $outputLine) {
            $outputLineValues = explode(' ', $outputLine);
            $processId = (int)$outputLineValues[0];
            $duration = $this->rawDurationToSeconds($outputLineValues[1]);
            
            if ($duration > $this->getDurationThreshold()) {
                $processesExeceedingDuration[] = $processId;
            }
        }
        
        return $processesExeceedingDuration;
    }
    
    
    /**
     * 
     * @param string $rawDuration Value from ps etime
     * @return int
     */
    private function rawDurationToSeconds($rawDuration) {
        if (substr_count($rawDuration, '-')) {
            $dayValues = explode('-', $rawDuration);
            $days = (int)$dayValues[0];
            $hourMinuteDayValues = explode(':', $dayValues[1]);
        } else {
            $days = 0;
            $hourMinuteDayValues = explode(':', $rawDuration);
        }
        
        switch (count($hourMinuteDayValues)) {
            case 3:
                $hours = (int)$hourMinuteDayValues[0];
                $minutes = (int)$hourMinuteDayValues[1];
                $seconds = (int)$hourMinuteDayValues[2];
                break;
            
            case 2:
                $hours = 0;
                $minutes = (int)$hourMinuteDayValues[0];
                $seconds = (int)$hourMinuteDayValues[1];                
                break;
            
            case 1:
                $hours = 0;
                $minutes = 0;
                $seconds = (int)$hourMinuteDayValues[0];                
                break;
        }
        
        $secondsInDay = 86400;
        $secondsInHour = 3600;
        $secondsInMinute = 60;
        
        return ($days * $secondsInDay) + ($hours * $secondsInHour) + ($minutes * $secondsInMinute) + $seconds;
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