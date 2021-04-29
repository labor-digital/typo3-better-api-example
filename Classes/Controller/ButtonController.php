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


namespace LaborDigital\Typo3BetterApiExample\Controller;

use LaborDigital\T3BA\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\ContentElement\ConfigureContentElementInterface;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\ContentElement\ContentElementConfigurator;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3BA\Tool\Tca\ContentType\Builder\ContentType;

class ButtonController extends BetterContentActionController
    implements ConfigureContentElementInterface,
               BackendPreviewRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(
        ContentElementConfigurator $configurator,
        ExtConfigContext $context
    ): void
    {
        $configurator
            ->setTitle('exampleBe.ce.button.title')
            ->setDescription('exampleBe.ce.button.desc')
            ->setBackendListLabelRenderer(['label', 'link']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('type')
                 ->setLabel('exampleBe.ce.button.field.type')
                 ->applyPreset()->select([
                    'default' => 'exampleBe.ce.button.field.type.default',
                    'cta' => 'exampleBe.ce.button.field.type.cta',
                ]);
            
            $type->getField('label')
                 ->setLabel('exampleBe.ce.button.field.label')
                 ->applyPreset()->input(['required']);
            
            $type->getField('link')
                 ->setLabel('exampleBe.ce.button.field.link')
                 ->applyPreset()
                 ->link(['required', 'allowLinkSets']);
            
            $type->getField('icon')
                 ->setLabel('exampleBe.ce.button.field.icon')
                 ->applyPreset()
                 ->relationImage(['maxItems' => 1, 'allowList' => 'svg']);
        });
    }
    
    public function indexAction(): string
    {
        return 'Button :D';
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return $context->getUtils()->renderFieldList([
            'label',
            'link',
            'icon',
        ]);
    }
    
}
