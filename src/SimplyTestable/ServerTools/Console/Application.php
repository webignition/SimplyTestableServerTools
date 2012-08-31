<?php

namespace SimplyTestable\ServerTools\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Finder\Finder;

class Application extends BaseApplication {    
    
    protected function getDefaultCommands() {
        $defaultCommands = parent::getDefaultCommands();
        
        $this->getNamespacePathExclusion();
        
        $finder = new Finder();
        
        $iterator = $finder
        ->files()
        ->name('*.php')              
        ->in(__DIR__ . '/../Command');
        
        foreach ($iterator as $file) {     
            if ($file->getFilename() != 'Command.php') {            
                $commandClassName = $this->getClassNameFromPath($file->getPathName());
                $defaultCommands[] = new $commandClassName;                
            }

        }
        
        return $defaultCommands;
    }    
    
    
    /**
     *
     * @param string $path
     * @return string
     */
    private function getClassNameFromPath($path) {
        $applicationRelativePath = str_replace($this->getNamespacePathExclusion(), '', realpath($path));
        $applicationRelativePath = substr($applicationRelativePath, 1);
        $applicationRelativePath = str_replace('.php', '', $applicationRelativePath);
        
        return str_replace(DIRECTORY_SEPARATOR, '\\', $applicationRelativePath);
    }
    
    
    /**
     * Get the part of path names to remove to get a class namespace
     * 
     * @return string
     */
    private function getNamespacePathExclusion() {
        return realpath(__DIR__ . '/../../..');
    }
    
    
}