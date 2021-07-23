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


namespace LaborDigital\T3baExample\FormEngine\Field;


use Doctrine\DBAL\Types\IntegerType;
use LaborDigital\T3ba\Tool\FormEngine\Custom\Field\AbstractCustomField;
use LaborDigital\T3ba\Tool\FormEngine\Custom\Field\CustomFieldDataHookContext;
use LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService;
use LaborDigital\T3ba\Tool\Tca\Builder\Logic\AbstractField;
use LaborDigital\T3ba\Tool\Tca\Builder\TcaBuilderContext;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaField;

/**
 * Class DemoField
 *
 * Make sure, that you either extend the AbstractCustomField or at least implement the CustomFieldInterface
 * in order for the system to work with your field.
 *
 * @package LaborDigital\T3baExample\FormEngine\Field
 */
class DemoField extends AbstractCustomField
{
    /**
     * This is the main html template we use for our form field.
     * We build a really simple "toggle" between "on" and "off" here.
     *
     * The template contains {{renderId}} and {{{hiddenField}}} which are automatically provided when
     * you use the renderTemplate() method. Those values are also provided if you use renderFluidTemplate().
     * Take a look at both methods for more information about preset variables.
     *
     * In our case: {{renderId}} returns the unique field id that was provided by the form engine
     * and {{{hiddenField}}} will render the complete html tag of the "hidden" input element the form engine uses
     * to store it's data. Mind the 3 braces to tell mustache that we don't want automatic escaping here.
     *
     * We also use the build in "translate" view helper that allows you to access the TYPO3 translation engine.
     */
    protected const TPL
        = <<<HTML
<div class="demoField" id="demoField_{{renderName}}">
    {{{hiddenField}}}
    <button class="demoField__button demoField__button--off btn btn-default">{{translate "exampleBe.field.demoField.button.off"}}</button>
    <button class="demoField__button demoField__button--on btn btn-default">{{translate "exampleBe.field.demoField.button.on"}}</button>
</div>
HTML;
    
    /**
     * @inheritDoc
     */
    public static function configureField(AbstractField $field, array $options, TcaBuilderContext $context): void
    {
        // We want to register a data hook that gets triggered when this field is stored in the database
        // You can simply do this by adding this class as a handler on the field.
        $field->registerSaveHook(static::class);
        
        // If we have a tca field we change both the type and the length of the database column.
        // By default custom fields are registered as a MEDIUMTEXT type in the database.
        if ($field instanceof TcaField) {
            $field->getColumn()->setType(new IntegerType())->setLength(4);
        }
    }
    
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        // The TYPO3 backend comes with support of loading js modules using requireJs,
        // so we will use this as our main source of JS delivery for form fields.
        // You can read more about it here: https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/JavaScript/RequireJS/Extensions/Index.html
        // The basic jist is, to load a js file we have to put it in the Resources/Public/JavaScript/OurFile.js (mind the capitalization)
        // and can load it using TYPO3/CMS/OurExtKey/OurFile, this works for nested directories as well.
        $this->context->registerRequireJsModule(
            'TYPO3/CMS/T3baExample/DemoField',
            // Additionally we can create a callback function which is executed for every field in the HTML markup
            // this makes it perfect for passing unique information like the current field id and name to the javascript implementation.
            // This is javascript code as a string, that should start with a "function" keyword. If the callback does not start with a function,
            // it will automatically be wrapped into one. In that case can use the "module" variable to access the export of the js module.
            'module("' . $this->context->getRenderName() . '")'
        // The code above is identical to writing this, but saves a bit of typing:
        // function(fieldJs){ fieldJs("' . $this->context->getRenderName() . '"); }
        );
        
        // If you need to load external script files, or for whatever reason can't, or don't want to use requireJs directly,
        // you can register any other script using the registerScript method. Note, that the script will only be loaded once per page, not per field!
        $this->context->registerScript('EXT:t3ba_example/Resources/Public/Assets/DemoField/demoField.js');
        
        // We use the mustache template engine provided by T3BA to render our template.
        // You could also use renderFluidTemplate() here to render a fluid template instead.
        // Its a matter of personal preference
        return $this->renderTemplate(static::TPL);
    }
    
    public function saveHook(CustomFieldDataHookContext $context): void
    {
        // As you can see, when you register a data hook on a custom field, you will receive the
        // CustomFieldDataHookContext object instead of the default DataHookContext.
        // This special context implementation provides you easy access to the field information or
        // field options
        $this->getService(FlashMessageRenderingService::class)
             ->addOk('Executed the save hook on field: ' . $context->getFieldName());
    }
    
}
