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
 * Last modified: 2021.02.22 at 09:30
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\FormEngine\Field;


use Doctrine\DBAL\Types\IntegerType;
use LaborDigital\T3BA\Tool\FormEngine\Custom\Field\AbstractCustomField;
use LaborDigital\T3BA\Tool\FormEngine\Custom\Field\CustomFieldDataHookContext;
use LaborDigital\T3BA\Tool\Rendering\FlashMessageRenderingService;
use LaborDigital\T3BA\Tool\Tca\Builder\Logic\AbstractField;
use LaborDigital\T3BA\Tool\Tca\Builder\TcaBuilderContext;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaField;

/**
 * Class DemoField
 *
 * Make sure, that you either extend the AbstractCustomField or at least implement the CustomFieldInterface
 * in order for the system to work with your field.
 *
 * @package LaborDigital\Typo3BetterApiExample\FormEngine\Field
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
<div class="demoField" id="{{renderId}}">
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
        // We can register additional assets for the backend for each field that gets rendered.
        // Those assets will only be used when the field is actually used.
        // Sadly we can't use the {{extKey}} placeholder here and therefore have to define the ext key statically here.
        $this->context->registerScript('EXT:typo3_better_api_example/Resources/Public/Assets/DemoField/demoField.js');
        
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
