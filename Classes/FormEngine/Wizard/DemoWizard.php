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


namespace LaborDigital\Typo3BetterApiExample\FormEngine\Wizard;


use LaborDigital\T3BA\Tool\FormEngine\Custom\Wizard\AbstractCustomWizard;

/**
 * Class DemoWizard
 *
 * The definition of wizards works quite similar to custom field types.
 * So I would suggest taking a look at {@link \LaborDigital\Typo3BetterApiExample\FormEngine\Field\DemoField}
 *
 * @package LaborDigital\Typo3BetterApiExample\FormEngine\Wizard
 */
class DemoWizard extends AbstractCustomWizard
{
    protected const TPL
        = <<<HTML
<div class="demoWizard" data-target="{{renderName}}">
    <button class="btn btn-default">{{translate "exampleBe.wizard.button.generate"}}</button>
</div>
HTML;
    
    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->context->registerScript('EXT:typo3_better_api_example/Resources/Public/Assets/DemoWizard/demoWizard.js');
        $this->context->registerStylesheet('EXT:typo3_better_api_example/Resources/Public/Assets/DemoWizard/demoWizard.css');
        
        return $this->renderTemplate(static::TPL);
    }
    
}
