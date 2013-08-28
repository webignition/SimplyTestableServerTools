<?php

namespace SimplyTestable\ServerTools\Tests\Command\Resque\Workers;

use SimplyTestable\ServerTools\Tests\Command\CommandTest;

class StartCommandTest extends CommandTest {
    
    const STARTING_WORKERS_FOR_PATTERN = '/^Starting workers for: [a-z-]+$/';
    const THERE_ARE_ALREADY_WORKERS_PATTERN = '/^There are already workers running for: [a-z-]+$/';
    const LIST_THEM_WITH_PATTERN = '/^List them with: php app\/console resque:workers:list --workerset [a-z-]+$/';
    const STOP_THEM_WITH_PATTERN = '/^Stop them with: php app\/console resque:workers:list --workerset [a-z-]+$/';
    
    public function testAll() {
        $startResult = $this->runConsole('resque:workers:start');        
        $this->assertEquals(0, $startResult['returnCode']);        
        
        /* @var $output \SimplyTestable\ServerTools\Console\MemoryWriter */
        $outputLines = $this->getOutputLines($startResult['output']);
        
        $this->assertEquals(6, count($outputLines));
        
        foreach ($outputLines as $outputLine) {
            $this->assertRegExp(self::STARTING_WORKERS_FOR_PATTERN, $outputLine);
        }
        
        $listResult = $this->runConsole('resque:workers:list');
    }
    
//    public function testWithValidWorkerSet() {
//        $result = $this->runConsole('resque:workers:start', array(
//            '--workerset' => 'app-general'
//        ));
//        
//        $this->assertEquals(0, $result['returnCode']);        
//    }    
//    
//    public function testWithInvalidWorkerSet() {
//        $result = $this->runConsole('resque:workers:start', array(
//            '--workerset' => 'foo'
//        ));
//        
//        $this->assertEquals(1, $result['returnCode']);
//    } 
    
//    public function testStartWhenWorkersAreAlreadyRunning() {
//        $this->runConsole('resque:workers:start'); 
//        $result = $this->runConsole('resque:workers:start');
//        
//        $outputLines = $this->getOutputLines($result['output']);
//        
//        $this->assertEquals(24, count($outputLines));
//        
//        foreach ($outputLines as $lineIndex => $outputLine) {
//            switch ($lineIndex % 4) {
//                case 0:
//                    $this->assertRegExp(self::STARTING_WORKERS_FOR_PATTERN, $outputLine);
//                    break;
//                
//                case 1:
//                    $this->assertRegExp(self::THERE_ARE_ALREADY_WORKERS_PATTERN, $outputLine);
//                    break;
//                
//                case 2:
//                    $this->assertRegExp(self::LIST_THEM_WITH_PATTERN, $outputLine);
//                    break;
//                
//                case 3:
//                    $this->assertRegExp(self::STOP_THEM_WITH_PATTERN, $outputLine);
//                    break;
//            }
//        }
//    }
}