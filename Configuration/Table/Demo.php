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
 * Last modified: 2021.01.13 at 19:34
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\Table;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;

class Demo implements ConfigureTcaTableInterface
{

    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->registerSaveHook(static::class, 'tableSaveHook');

        $withoutTab = $table->getField('withoutTab')
                            ->setLabel('My Field')
                            ->registerSaveHook(static::class, 'fieldSaveHook')
                            ->addConfig(['type' => 'input']);

        $type = $table->getType(2);
        $type->registerSaveHook(static::class, 'typeSaveHook');

        $type->getField('withoutTab')->inheritFrom($withoutTab)
             ->registerSaveHook(static::class, 'typeFieldSaveHook');

        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('testField')
                 ->applyPreset()->input();
        });

    }

    public function tableSaveHook()
    {
        dbge('ON TABLE SAVE!', func_get_args());
    }

    public function typeSaveHook()
    {
        dbge('ON TYPE SAVE!', func_get_args());
    }

    public function fieldSaveHook()
    {
        dbge('ON FIELD SAVE!', func_get_args());
    }

    public function typeFieldSaveHook()
    {
        dbge('ON TYPE FIELD SAVE!', func_get_args());
    }
}
