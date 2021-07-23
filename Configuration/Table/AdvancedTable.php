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


namespace LaborDigital\T3baExample\Configuration\Table;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;
use LaborDigital\T3baExample\Configuration\Table\Article\ArticleTable;
use LaborDigital\T3baExample\Configuration\Table\Article\AuthorTable;
use LaborDigital\T3baExample\FormEngine\Field\DemoField;
use LaborDigital\T3baExample\FormEngine\UserFunc\DemoUserFunc;
use LaborDigital\T3baExample\FormEngine\Wizard\DemoWizard;

class AdvancedTable implements ConfigureTcaTableInterface
{
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
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
                    'basePid' => '@pid.storage.news.article',
                ]);
            
            $type->getField('group_with_multi_base_pid')
                // This works with multiple tables as well
                 ->applyPreset()->relationGroup([ArticleTable::class, 'pages'], [
                    'basePid' => [
                        ArticleTable::class => '@pid.storage.news.article',
                        'pages' => 1,
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
                    'default' => 'exampleBe.g.dummy',
                    'placeholder' => 'exampleBe.g.dummy',
                ]);
            
            $type->getField('custom_field')
                // Normally it is a real pain to register a new type of input element in the form engine.
                // T3BA provides a preset called "customField" that allows you create a new field type with ease.
                // take a look at this simple implementation of a toggle field
                 ->applyPreset()->customField(DemoField::class, ['someOptions' => true]);
            
            // Alternatively, if you are extra-fancy you could register a custom preset
            // for your field type and allow the table author to find your type that way.
            // See LaborDigital\T3baExample\FormEngine\FieldPreset\Demo to find out how to do that
            $type->getField('custom_field_with_preset')
                 ->applyPreset()->demoToggle();
            
            $type->getField('custom_wizard')
                 ->applyPreset()->input()
                // The creation of custom field wizards works in a similar way to fields
                // instead of customField() just use the customWizard() preset.
                 ->applyPreset()->customWizard(DemoWizard::class);
            
            // It is super easy to create repeatable sections through the inline relation type.
            // As you see, I created a dedicated section table which is used as target here.
            // The table is marked as "hidden" and therefore will not show up in the list view.
            $type->getField('sections')
                 ->applyPreset()->inline(DemoSectionTable::class);
            
            // You know, whats also super easy? Adding content elements!
            // Under the hood this is still an inline relation but automatically links to the tt_content table.
            // Additionally it has some tricks up it's sleeve, if you go ahead and take a look at that field in
            // the backend, you will see, that the "new" button automatically opens a fancy new content element
            // wizard, instead of simply dropping in a new row with the first CType.
            // The tt_content table will be automatically extended to contain additional fields for the inline
            // and inline sorting value.
            $type->getField('content')
                 ->applyPreset()->inlineContent();
            
            // Furthermore you can predefine which CType should be used for added records by default.
            // This is nice if you want to create a list of multiple of the same elements
            $type->getField('buttons')
                 ->applyPreset()->inlineContent(['defaultCType' => 't3baexample_button']);
        });
    }
    
}
