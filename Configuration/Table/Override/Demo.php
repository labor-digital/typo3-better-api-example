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
 * Last modified: 2021.01.30 at 21:15
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\Table\Override;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;
use LaborDigital\Typo3BetterApiExample\EventHandler\DataHook\DemoDataHooks;

class Demo implements ConfigureTcaTableInterface
{

    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // Overrides work exactly like in the TYPO3 core.
        // They are processed after TYPO3 loaded the Configuration/TCA/Overrides tca files.
        // Therefore you can create a table like in the Configuration/Table/Demo class
        // override some TCA values in Configuration/TCA/Overrides/demo.php
        // an then come back here to edit those changes using the table builder again.
        $table->registerSaveHook(DemoDataHooks::class, 'tableOverrideSaveHook');

        // Everything in an override works in the same way a normal table would.
        // So you can simply grab a field in a type and do stuff with it.
        // You can add new types, modify existing types modify all of their children
        // based on your liking.
        $table->getType(1)->getField('override_field')
              ->setLabel('exampleBe.t.demo.field.overrideField')
              ->setDescription('exampleBe.t.demo.field.overrideField.desc')
              ->applyPreset()
              ->checkbox([
                  'default' => true,
                  'toggle',
              ])
              ->moveTo('0');
    }

}
