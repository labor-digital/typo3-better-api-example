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
 * Last modified: 2021.07.16 at 17:18
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Configuration\Table;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;
use LaborDigital\T3baExample\FormEngine\Wizard\DemoWizard;

class DemoSectionTable implements ConfigureTcaTableInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // Using the hidden option is great to create tables that can only show up
        // as part of inline relations. This is good to use as repeatable sections.
        // As you can see, hidden tables don't really need any kind of configuration.
        // However, to make it easier for your editors, I would suggest adding at least a label column,
        // otherwise the sections only get the UID as readable label.
        $table->setHidden();
        
        $type = $table->getType();
        
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('custom_field')
                 ->applyPreset()->demoToggle();
            
            $type->getField('custom_wizard')
                 ->applyPreset()->input()
                 ->applyPreset()->customWizard(DemoWizard::class);
        });
    }
    
}