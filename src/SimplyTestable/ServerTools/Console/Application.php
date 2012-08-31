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
    
    private $configurationPath;
    
    private $configuration;
    
    public function __construct($configurationPath) {
        $this->configurationPath = $configurationPath;
        parent::__construct();
    }    
    
    /**
     *
     * @return \stdClass
     */
    public function getConfiguration() {
        if (is_null($this->configuration)) {
            $this->configuration = json_decode(file_get_contents($this->configurationPath));
        }
        
        return $this->configuration;
    }
    
    
    protected function getDefaultCommands() {
        $defaultCommands = parent::getDefaultCommands();
        
        $this->getNamespacePathExclusion();
        
        $finder = new Finder();
        
        $iterator = $finder
        ->files()
        ->name('*.php')              
        ->in(__DIR__ . '/../Command');
        
        foreach ($iterator as $file) {
            if (!preg_match('/^Abstract/', $file->getFilename())) {
                $commandClassName = $this->getClassNameFromPath($file->getPathName());
                $command = new $commandClassName;                
                $defaultCommands[] = new $command;                    
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