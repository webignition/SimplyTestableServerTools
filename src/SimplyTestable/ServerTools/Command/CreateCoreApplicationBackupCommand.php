<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCoreApplicationBackupCommand extends AbstractCommand
{
    const DEFAULT_DURATION_THRESHOLD = 300;
    
    
    protected function configure()
    {
        $this
            ->setName('create-core-application-backup')
            ->setDescription('Create a safe backup of the core application')
            ->setHelp(<<<EOF
Create a safe backup of the core application
EOF
        );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        //parent::execute($input, $output);
    }




//    protected function execute(InputInterface $input, OutputInterface $output) {
////        $this->setInput($input);
////        $this->setOutput($output);
//        
//        return 0;
//        
//        
//        
////        $processIdsToKill = $this->getProcessIdsForThresholdExceedingJobs();
////        
////        foreach ($processIdsToKill as $processIdToKill) {
////            exec('kill -9 '.$processIdToKill);
////        }
//    }

    
    
    
//    /**
//     * 
//     * @return int
//     */
//    private function getDurationThreshold() {
//        $durationThresholdOption = $this->getDurationThresholdOption();
//        if (!is_null($durationThresholdOption)) {
//            return $durationThresholdOption;
//        }
//        
//        return self::DEFAULT_DURATION_THRESHOLD;
//    }
//    
//    
//    /**
//     * 
//     * @return int
//     */
//    private function getDurationThresholdOption() {
//        return filter_var($this->getInput()->getOption('durationThreshold'), FILTER_VALIDATE_INT, array(
//            'options' => array(
//                'default' => null,
//                'min_range' => 0
//            )
//        ));        
//    }
   

}