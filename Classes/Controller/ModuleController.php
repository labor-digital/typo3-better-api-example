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


use LaborDigital\T3ba\ExtBase\Controller\BetterActionController;
use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Module\ConfigureModuleInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Module\ModuleConfigurator;
use LaborDigital\T3ba\Tool\Rendering\BackendRenderingService;
use LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class ExtBaseModuleController
 *
 * Please note that is important to use BetterActionController in order for the some features to work!
 *
 * @package LaborDigital\T3baExample\Controller
 */
class ModuleController extends BetterActionController
    implements ConfigureModuleInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    
    /**
     * @var \LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService
     */
    protected $flashMessageService;
    
    public function __construct(FlashMessageRenderingService $flashMessageService)
    {
        $this->flashMessageService = $flashMessageService;
    }
    
    /**
     * @inheritDoc
     */
    public static function configureModule(ModuleConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setSection('file');
    }
    
    public function indexAction()
    {
        // Show a nice flash message if we don't have any set
        if (! $this->flashMessageService->hasMessages()) {
            $this->flashMessageService->addInfo('Hello there, this is a demo module :)');
        }
        
        $this->view->assignMultiple(
            [
                'title' => 'Pages Overview',
                'records' => $this->getService(BackendRenderingService::class)
                                  ->renderDatabaseRecordList('pages', ['title'], ['pid' => 0]),
            ]
        );
    }
    
    public function specialAction()
    {
        $this->flashMessageService->addInfo('This is a special action which was logged!');
        
        // The matching logger was registered in the CommonConfig ext config file
        $this->logger->info('Special action was opened in the backend');
    }
    
    public function messageAction()
    {
        $this->getService(FlashMessageRenderingService::class)
             ->addOk('You clicked on a button :)', 'You did it!', ['storeInSession']);
        
        // We redirect here, so we can use the "storeInSession" flag.
        $this->redirect('index');
    }
}
