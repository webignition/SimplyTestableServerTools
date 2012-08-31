<?php
namespace SimplyTestable\ServerTools\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResqueWorkersStartCommand extends Command
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
    
    
    /**
     *
     * @return \stdClass
     */
    private function getWorkerSets()
    {
        return $this->getApplication()->getConfiguration()->{'resque-workers'}->sets;
    }
    
    
    /**
     *
     * @return array
     */
    private function getWorkerSetNames() {
        $workerSetNames = array();
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {
            $workerSetNames[] = $name;
        }
        
        return $workerSetNames;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $workerSet = $input->getOption('workerset');
        if (!is_null($workerSet) && !in_array($workerSet, $this->getWorkerSetNames())) {
            $output->writeln('workset "'.$workerSet.'" not valid, check app/config.json for valid options');
            return false;
        }
        
        $output->setDecorated(true);
        foreach ($this->getWorkerSets() as $name => $workerSetDetails) {
            $command = 'cd ' . $workerSetDetails->path . ' && ' . $this->getApplication()->getConfiguration()->{'resque-workers'}->commands->start->{$workerSetDetails->type};
            exec($command . ' 2>&1 &');
        }
    }
}