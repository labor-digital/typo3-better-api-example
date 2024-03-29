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


namespace LaborDigital\T3baExample\Configuration\Table\Override;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\ExtConfigHandler\Table\TcaTableNameProviderInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;

class PagesTable implements ConfigureTcaTableInterface, TcaTableNameProviderInterface
{
    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        // We specifically want to override the pages table here
        // If we would not define this here, the logic would automatically namespace the table to our extension
        return 'pages';
    }
    
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $type = $table->getType(1);
        $type->getTab(0)->addMultiple(function () use ($type) {
            $type->getField('my_field')->applyPreset()->input();
        });
    }
    
}
