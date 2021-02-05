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
use LaborDigital\Typo3BetterApiExample\Domain\Model\DemoManuallyMapped;
use LaborDigital\Typo3BetterApiExample\EventHandler\DataHook\DemoDataHooks;

class Demo implements ConfigureTcaTableInterface
{

    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // The database table is represented by the $table object
        // if you create a new table, like it's the case here
        // you will start with a slim default configuration you can work with.
        //
        // The database name is automatically inferred by the name of your PHP class.
        // For this class our table will be called tx_typo3betterapiexample_domain_model_demo
        // which is a based on the current extensions vendor, extKey and the php namespace of the
        // default model.
        //
        // To check that make a var_dump() on $table->getTableName(); which gives you the
        // name of the database table you are currently configuring.
        //
        // You can also provide namespaces in your table class definition,
        // take a look at LaborDigital\Typo3BetterApiExample\ConfigurationTable\Article\Article
        // or "Author" in the same namespace. Those tables will incorporate the "article" namespace
        // in their table name automatically: tx_typo3betterapiexample_domain_model_article_article.

        // It is always advisable that you provide a translation label
        // as a title for your table, because otherwise the TYPO3 core has issues
        // to link the table to an extension. You can either use the common LLL:... definition
        // or the short "namespace" driven design provided by better api.
        $table->setTitle('exampleBe.t.demo.title');

        // There are lots of different configuration options you can perform on the TCA
        // use the auto completion of your IDE to take a look at them. They should all have an extensive
        // documentation in their code. Type $table-> to start :)

        // By default records can only be created in "folder" pages.
        // To add a record on a default page you can use this simple switch
        $table->setAllowOnStandardPages();

        // Non-standard conform domain models can be mapped without typoScript with ease
        // You can optionally even map table fields to domain properties if they are not conform with the TYPO3 standard
        $table->addModelClass(DemoManuallyMapped::class, ['input' => 'mappedInput']);

        // This hook will be executed every time this table gets saved.
        // It will be triggered in ANY registered type.
        $table->registerSaveHook(DemoDataHooks::class, 'tableSaveHook');

        // We want our table to have a type that can be switched by the backend user.
        // Here we provide two different type options for the generated field to show to the user.
        // This will automatically add the field to the default type.
        //
        // Note: In order for the field to show up in other types you have to manually load it by using getField($fieldName)
        // The configuration will then be inherited from this.
        //
        // Note 2: By default the "type" column will be added to the top of the first available tab in the form.
        // You can always move the field later by using getField($fieldName)->moveTo('otherField');
        $table->setTypeColumn('type', [
            0 => 'NT: Default',
            1 => 'NT: With special field',
        ]);

        // DEFAULT TYPE (Typename: 0)
        // =============================
        $type = $table->getType(); // An empty parameter will always return the default type

        // Tabs are represented by their index when the "showitem" string of the type was
        // parsed and converted into an object structure. Every tab keeps it's id even if
        // you move them around in the object.
        //
        // NOTE HOWEVER: If you move a tab to another position the tab will have another id
        // when the overrides for the table are processed! This is a limitation of the TYPO3
        // core I could not find a good solution for, yet.
        //
        // You can either get your fields using the $table->getField() method and position them with the
        // moveTo() method (see below for further information), or you can select a container element,
        // or a "palette" how TYPO3 calls them and add multiple children to it at once.
        $type->getTab(0)->addMultiple(static function () use ($type) {
            // getField() allows you access to all fields inside your form
            // if the field is already defined the matching reference will be returned
            // if you requiring a new field, a blank definition is automatically created for you
            $type->getField('headline')
                // In most situations your forms have common elements you use over and over
                // therefore I created a list of presets that can be applied to a field.
                // Presets can also have options to make them more dynamic.
                // To use a preset simply call the applyPreset() method and
                // select one of the available presets from your IDE's auto completion.
                // You can also add your own presets using the preset definition api.
                 ->applyPreset()->input(['maxLength' => 50, 'required']);
        });

        // While it is possible to add fields without a tab definition I would not
        // recommend this option. In a case like this, the field gets added to the last tab in your form.
        $type->getField('input')
            // This hook will be executed every time the DEFAULT TYPE of the table gets saved
            // AND ONLY if data for the field was provided. If the field is not in the processed data
            // this hook will be ignored
             ->registerSaveHook(DemoDataHooks::class, 'fieldSaveHook')
             ->applyPreset()->input()
            // If you are not happy with the position of a field you can always move ALL elements
            // in your form to other positions using the moveTo() method.
            // You can move elements relative to other fields, palettes/containers or tabs
            // and you can define if an element should be added before or after the reference.
             ->moveTo('after:headline');

        // DEFAULT TYPE (Typename: 1)
        // =============================
        $type = $table->getType(1);

        // This hook will be executed every time this table gets saved and the data is marked for THIS type.
        // If data is stored for another type this hook is ignored.
        $type->registerSaveHook(DemoDataHooks::class, 'typeSaveHook');

        $type->getTab(0)->addMultiple(static function () use ($type) {
            // Field definitions can be inherited from the default type by just requiring them
            // Note: Fields that are inherited from the default type are placed on the current position in the form.
            $type->getField('type');

            // Palettes are a subset of fields that can be placed side-by-side to each other
            // They work quite similar to tab, but can be nested into one.
            $type->getPalette('title')
                 ->setLabel('exampleBe.t.demo.palette.title')
                 ->addMultiple(static function () use ($type) {
                     // We inherit the "headline" field from the default type by requiring it here
                     // we will also store the reference for later...
                     $headline = $type->getField('headline');

                     // If you want to start with a blank slate in a type, and don't want to
                     // inherit something from the default type you can do so by resetting the raw value:
                     // $headline->setRaw([])->applyPreset()->select();

                     // It is also possible to inherit the configuration from one field to another
                     $type->getField('sub_headline')->inheritFrom($headline);
                 });

            // You can also simply add new fields to a type that are not part of the default type
            $type->getField('special_field')
                 ->applyPreset()->input();

            // It is also possible to extend fields
            $type->getField('input')
                // This hook will be executed every time THIS type of the table gets saved
                // AND ONLY if data for the field was provided. If the field is not in the processed data
                // this hook will be ignored
                //
                // Please note, that because we inherit the default type configuration for this field
                // this field will actually trigger two save hooks. First the one that was
                // configured in the default type, and second the one configured here.
                // If you don't wand to inherit the dataHooks when you add a field in another type
                // you can call $field->clearDataHooks() to remove all existing hook definitions
                 ->registerSaveHook(DemoDataHooks::class, 'typeFieldSaveHook');

        });

    }
}
