<?php

namespace SimplyTestable\ServerTools\Tests\Command\Resque\Workers;

use SimplyTestable\ServerTools\Tests\Command\CommandTest;

class ListCommand extends CommandTest {           
    
    public function testAll() {
        $this->runConsole('resque:workers:start');        
        sleep(1);

        $listResult = $this->runConsole('resque:workers:list');        
        $parsedListOutput = $this->parseListOutput($this->getOutputLines($listResult['output']));
        
        $this->assertEquals(6, count($parsedListOutput['processLines']));
        
        foreach ($parsedListOutput['processLines'] as $processSet) {
            $this->assertTrue(count($processSet) > 0);
        }        
    }

    
    public function testWithValidWorkerSet() {
        $startResult = $this->runConsole('resque:workers:list', array(
            '--workerset' => 'app-general'
        ));
        
        $this->assertEquals(0, $startResult['returnCode']);        
        
        $startOutputLines = $this->getOutputLines($startResult['output']);
        $this->assertRegExp(self::LISTING_WORKERS_FOR_PATTERN, $startOutputLines[0]);

    }    
    
    public function testWithInvalidWorkerSet() {
        $startResult = $this->runConsole('resque:workers:list', array(
            '--workerset' => 'foo'
        ));
        
        $this->assertEquals(1, $startResult['returnCode']);
        $startOutputLines = $this->getOutputLines($startResult['output']);
        $this->assertRegExp(self::WORKERSET_INVALID_PATTERN, $startOutputLines[0]);        
    }
    
    
}