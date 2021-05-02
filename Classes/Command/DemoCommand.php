<?php
/*
 * Copyright 2021 LABOR.digital
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2021.04.29 at 22:21
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Command;


use LaborDigital\T3BA\ExtConfigHandler\Command\ConfigureCliCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DemoCommand
 *
 * Configuring a command is easy as pie! Create a class in /Classes/Command,
 * let it extend the "Command" class, provided by the symfony console component and
 * also implement the ConfigureCliCommandInterface. The command configuration is done using
 * the "configure()" method provided by the command. All information are provided to the TYPO3 implementation.
 *
 * @package LaborDigital\T3baExample\Command
 */
class DemoCommand extends Command implements ConfigureCliCommandInterface
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('A simple command that could do whatever you want, imagine the possibilities!');
    }
    
    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world, this is a demo command :D');
        
        return 0;
    }
    
}
