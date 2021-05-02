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


namespace LaborDigital\T3baExample\Controller;


use LaborDigital\T3BA\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererInterface;

class PluginBController extends BetterContentActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator
            ->setTitle('exampleBe.p.pluginB.title')
            ->setDescription('exampleBe.p.pluginB.desc');
        
        // As you see, we don't provide any actions to the configurator,
        // but the script will automatically find all public methods ending with "Action" and register
        // them as viable actions for you. The order of the definition inside the controller class
        // defines the order in the actions array.
        // This is inferred automatically: $configurator->setActions(['index']);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return $this->getFluidView()
                    ->assign('argument', 'My argument :)')
                    ->assign('html',
                        // You can render a nice list of fields from your record to show the user a overview
                        // of wat was configured for this element
                        $context->getUtils()->renderFieldList(['header', 'list_type', 'sys_language_uid'])
                    );
    }
    
    public function indexAction()
    {
        dbge('INDEX');
    }
    
    
}
