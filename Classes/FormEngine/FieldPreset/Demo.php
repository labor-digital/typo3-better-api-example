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
 * Last modified: 2021.02.22 at 12:42
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\FormEngine\FieldPreset;


use LaborDigital\T3BA\Tool\FormEngine\Custom\Field\CustomFieldPresetTrait;
use LaborDigital\T3BA\Tool\Tca\Builder\FieldPreset\AbstractFieldPreset;
use LaborDigital\Typo3BetterApiExample\FormEngine\Field\DemoField;

/**
 * Class Demo
 *
 * Field presets must be located at /Classes/FormEngine/FieldPreset AND extend the AbstractFieldPreset
 * class in order to be found by the preset collector.
 *
 * @package LaborDigital\Typo3BetterApiExample\FormEngine\FieldPreset
 */
class Demo extends AbstractFieldPreset
{
    // You can use the custom field preset trait in your preset
    // in order to create presets for your custom form elements in a breeze
    use CustomFieldPresetTrait;

    /**
     * Converts the field into a custom element using our demo field type
     */
    public function applyDemoToggle(): void
    {
        $this->applyCustomElementPreset(DemoField::class);
    }

}
