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
 * Last modified: 2021.02.21 at 18:48
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\Table;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\Article\ArticleTable;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\Article\AuthorTable;
use LaborDigital\Typo3BetterApiExample\FormEngine\Field\DemoField;
use LaborDigital\Typo3BetterApiExample\FormEngine\UserFunc\DemoUserFunc;
use LaborDigital\Typo3BetterApiExample\FormEngine\Wizard\DemoWizard;

class AdvancedTable implements ConfigureTcaTableInterface
{
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // @todo rename parent() to getExtConfigContext() to follow the naming schema
//        $table->getContext()->getExtConfigContext()->getTypoContext()->getRootContext();

        // This is an example of some advanced usage scenarios that are implemented in T3BA
        // and might be new if you are normally work with the TYPO3 core.
        $table->setTitle('exampleBe.t.advanced.title');
        $table->setLabelColumn('title');
        $type = $table->getType();

        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('title')
                 ->setReadOnly(true)
                // You can create fully automated fields that get created when your record is created
                // using callable default user functions
                 ->applyPreset()->input(['default' => [DemoUserFunc::class, 'generateDefaultTitle']]);

            $type->getField('group_with_base_pid')
                // Group relations can provide a base pid, this will automatically
                // tell the TYPO3 backend that it should open a specific storage page
                // when the editor opens the "record" browser in the backend.
                 ->applyPreset()->relationGroup(ArticleTable::class, [
                    'basePid' => '@pid.storage.article',
                ]);

            $type->getField('group_with_multi_base_pid')
                // This works with multiple tables as well
                 ->applyPreset()->relationGroup([ArticleTable::class, 'pages'], [
                    'basePid' => [
                        ArticleTable::class => '@pid.storage.article',
                        'pages'             => 1,
                    ],
                ]);

            $type->getField('upload_with_basedir')
                // You can provide a base directory for a file field
                // If the directory not exists, it will be automatically created for you
                 ->applyPreset()->relationFile(['baseDir' => '/fileadmin/demo_upload']);

            $type->getField('group_with_reload')
                // In a vanilla TYPO3 group fields can not trigger a reload on change
                // T3BA provides a compatibility patch to fix this issue.
                 ->setReloadOnChange(true)
                 ->applyPreset()->relationGroup(AuthorTable::class, ['maxItems' => 1]);

            $type->getField('translated_default')
                // TYPO3 can neither use translated values for defaults, nor for placeholders.
                // T3BA provides patches for this issue as well.
                 ->applyPreset()->input([
                    'default'     => 'exampleBe.g.dummy',
                    'placeholder' => 'exampleBe.g.dummy',
                ]);

            $type->getField('custom_field')
                // Normally it is a real pain to register a new type of input element in the form engine.
                // T3BA provides a preset called "customField" that allows you create a new field type with ease.
                // take a look at this simple implementation of a toggle field
                 ->applyPreset()->customField(DemoField::class);

            // Alternatively, if you are extra-fancy you could register a custom preset
            // for your field type and allow the table author to find your type that way.
            // See LaborDigital\Typo3BetterApiExample\FormEngine\FieldPreset\Demo to find out how to do that
            $type->getField('custom_field_with_preset')
                 ->applyPreset()->demoToggle();

            $type->getField('custom_wizard')
                 ->applyPreset()->input()
                // The creation of custom field wizards works in a similar way to fields
                // instead of customField() just use the customWizard() preset.
                 ->applyPreset()->customWizard(DemoWizard::class);
        });
    }

}
