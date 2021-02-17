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
 * Last modified: 2021.02.12 at 21:41
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\Table\Override;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\ExtConfigHandler\Table\TcaTableNameProviderInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;

class ContentTable implements ConfigureTcaTableInterface, TcaTableNameProviderInterface
{
    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        // We specifically want to override the tt_content table here
        // If we would not define this, the logic would automatically namespace the table to our extension.
        // Note, that you also need to implement the "TcaTableNameProviderInterface" interface on your configuration
        // class in order for it to detect that you want to override the table name
        return 'tt_content';
    }

    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // We don't really want to change anything on the tt_content table,
        // but we want to tell the table, that our "Content" model, should be considered part of it.
        // This allows extBase to map this model to the correct table - no typoScript needed :D
        $table->addModelClass(\LaborDigital\Typo3BetterApiExample\Domain\Model\Content::class);
    }
}
