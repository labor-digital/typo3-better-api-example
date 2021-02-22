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
 * Last modified: 2021.02.22 at 18:12
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Command;


use LaborDigital\T3BA\Core\Di\ContainerAwareTrait;
use LaborDigital\T3BA\ExtConfigHandler\Command\ConfigureCliCommandInterface;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\AdvancedTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TmpCommand extends Command implements ConfigureCliCommandInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cs()->dataHandler->getRecordDataHandler(AdvancedTable::class)->makeNew([], 5);

        return 0;
    }

}
