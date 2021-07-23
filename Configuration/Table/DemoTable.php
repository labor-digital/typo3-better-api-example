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
use LaborDigital\T3baExample\Domain\Model\DemoManuallyMapped;
use LaborDigital\T3baExample\EventHandler\DataHook\DemoDataHooks;

class DemoTable implements ConfigureTcaTableInterface
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
        // take a look at LaborDigital\T3baExample\ConfigurationTable\Article\Article
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
        $table->registerModelClass(DemoManuallyMapped::class, ['input' => 'mappedInput']);
        
        // This hook will be executed every time this table gets saved.
        // It will be triggered in ANY registered type.
        $table->registerSaveHook(DemoDataHooks::class, 'tableSaveHook');
        
        // To make our table more readable in the backend lists we can simply
        // define the "label column" to "headline" -> We will create the column a bit further down the line.
        $table->setLabelColumn('headline');
        
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
        
        // When you want to add a new tab to an existing type you have two options.
        // Option a.) use getType() with a fairly high id, from which you know it will not exist
        // or use option b.) use getNewTab() which will automatically add a new tab to the list
        //
        // Please note that new tabs are always added to the end of the list.
        // You can simply move them using ->moveTo() later.
        $type->getNewTab()
             ->addMultiple(static function () use ($type, $context) {
                 // Flex-Forms are a special kind of noodle,
                 // as they are basically a form inside another form. With their own
                 // special features, like repeatable containers.
                 //
                 // Therefore they have their own object representation you can
                 // access on any field with "getFlexFormConfig()"
                 $flexConfig = $type->getField('flex')->getFlexFormConfig();
            
                 // Did you know, that each TCA field can have a multitude of flex-forms on a single field?
                 // Yes indeed! Therefore you have to select the correct "data-structure"
                 // before you can work with the flex form. To make it easier for your daily live
                 // you can just call getStructure() without arguments and receive one called "default"
                 // automatically.
                 $form = $flexConfig->getStructure();
            
                 // Now, the object stored in $form will handle in exactly the same way
                 // like your form before. You can use the auto-completion of your IDE to explore
                 // it in detail.
            
                 // You can now either configure the flex form using the object oriented way,
                 // or even load a flex form configuration from a .xml file or a plain string.
                 // To do so, just tell the structure to load a definition by calling $form->loadDefinition().
                 $form->loadDefinition('file:Example.xml');
            
                 // Here we will load the flex form definition of Configuration/FlexForms/Example.xml in your
                 // current extension directory. Please note, that we will only use this xml as a base
                 // and will not edit it in any shape or form. All changes you do to the flex form
                 // are stored in a separate xml file.
            
                 // So lets begin and adjust the loaded form by injecting a dataHook for a field
                 $form->getField('settings.mode')
                      ->registerSaveHook(DemoDataHooks::class, 'flexFormFieldSaveHook');
            
                 // We can, of course not only register save hooks, but also "form" hooks.
                 // Those hooks allow you to modify the form configuration right before it is rendered
                 // by the TYPO3 form engine.
                 //
                 // As you see, flex forms have a special quirk to them, as fields with the same name
                 // may be registered multiple time. To access a field you can work with paths
                 // that tell the getField method where to look for a field.
                 // The parts of the path are separated using an arrow "->"
                 // If you know that you only have a single field with a specific name, you don't need
                 // to use paths, but they will come in handy in some edge cases
                 $form->getField('Sorting->settings.sorting->name')
                      ->registerFormHook(DemoDataHooks::class, 'flexFormFieldInSectionFormHook');
            
             })
            // Move this tab right after the first tab (id: 0)
            // The "after" is implicit, as it is the default value if you just define an identifier.
            // As you learned above, you could also provide a before:0 to move the tab in front of tab 0.
             ->moveTo('0');
        
        // SPECIAL TYPE (Typename: 1)
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
                // If you don't want to inherit the dataHooks when you add a field in another type
                // you must call $field->clearDataHooks() to remove all existing hook definitions
                 ->registerSaveHook(DemoDataHooks::class, 'typeFieldSaveHook');
            
        });
        
        // While table columns are automatically configured when you are using the applyPreset() function,
        // there are some SQL operations that have to be performed on a table level.
        // This includes the creation of indexes or adding columns that are not part of the
        // TCA definition. For that you can access the schema directly from the table object.
        //
        // Please note, that columns may be added but not retrieved or checked for on the table schema.
        // This is, because the column definitions depend on the table configuration object
        // and may therefore differ from the initial state the table had when it was loaded.
        
        $table->getSchema()
            // This is a normal Doctrine Table object, so you can perform any operation you like.
              ->addIndex(['special_field'])
              ->addColumn('not_mapped_column', 'integer', ['length' => 4]);
        
    }
}
