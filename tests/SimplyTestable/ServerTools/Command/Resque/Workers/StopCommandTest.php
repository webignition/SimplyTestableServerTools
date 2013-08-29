<?php

namespace SimplyTestable\ServerTools\Tests\Command\Resque\Workers;

use SimplyTestable\ServerTools\Tests\Command\CommandTest;

class StopCommandTest extends CommandTest {
    
    public function testAll() {
        $this->runConsole('resque:workers:start'); 
        sleep(1);
        
        $preListResult = $this->runConsole('resque:workers:list');
        $parsedPreListOutput = $this->parseListOutput($this->getOutputLines($preListResult['output']));        
        
        $this->assertEquals(6, $parsedPreListOutput['counts']['headerLines']);
        $this->assertTrue($parsedPreListOutput['counts']['processLines'] >= $parsedPreListOutput['counts']['headerLines']);        
        
        $stopResult = $this->runConsole('resque:workers:stop');
        sleep(1);
        
        $this->assertEquals(0, $stopResult['returnCode']);             
        $this->assertEquals(6, count($this->getOutputLines($stopResult['output'])));
        
        $postListResult = $this->runConsole('resque:workers:list');
        $parsedPostListOutput = $this->parseListOutput($this->getOutputLines($postListResult['output']));        
        
        $this->assertEquals(6, $parsedPostListOutput['counts']['headerLines']);
        $this->assertEquals(0, $parsedPostListOutput['counts']['processLines']);
    }   
    
    public function testWithValidWorkerSet() {
        $workerSet = 'app-general';
        $this->runConsole('resque:workers:start', array(
            '--workerset' => $workerSet
        ));           
        sleep(1);
        
        $stopResult = $this->runConsole('resque:workers:stop', array(
            '--workerset' => 'app-general'
        )); 
        
        $this->assertEquals(0, $stopResult['returnCode']);
        $this->assertEquals(1, count($this->getOutputLines($stopResult['output'])));
    }    
    
    public function testWithInvalidWorkerSet() {
        $stopResult = $this->runConsole('resque:workers:stop', array(
            '--workerset' => 'foo'
        ));
        
        $this->assertEquals(1, $stopResult['returnCode']);
        $stopOutputLines = $this->getOutputLines($stopResult['output']);
        $this->assertRegExp(self::WORKERSET_INVALID_PATTERN, $stopOutputLines[0]);        
    }   
}