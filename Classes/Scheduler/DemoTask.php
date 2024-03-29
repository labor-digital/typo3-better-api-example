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


namespace LaborDigital\T3baExample\Scheduler;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Scheduler\Task\ConfigureTaskInterface;
use LaborDigital\T3ba\ExtConfigHandler\Scheduler\Task\TaskConfigurator;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class DemoTask extends AbstractTask implements ConfigureTaskInterface
{
    /**
     * @inheritDoc
     */
    public static function configure(TaskConfigurator $taskConfigurator, ExtConfigContext $context): void
    {
        $taskConfigurator->setDescription('A simple task that wishes you a pleasant day');
    }
    
    /**
     * @inheritDoc
     */
    public function execute()
    {
        echo 'I hope you have a great day :D!' . PHP_EOL;
        
        return true;
    }
    
}
