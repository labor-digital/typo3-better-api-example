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


namespace LaborDigital\T3baExample\Configuration\ExtConfig;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Core\ConfigureTypoCoreInterface;
use LaborDigital\T3BA\ExtConfigHandler\Core\TypoCoreConfigurator;
use LaborDigital\T3BA\ExtConfigHandler\Fluid\ConfigureFluidInterface;
use LaborDigital\T3BA\ExtConfigHandler\Fluid\FluidConfigurator;
use LaborDigital\T3BA\ExtConfigHandler\Translation\ConfigureTranslationInterface;
use LaborDigital\T3BA\ExtConfigHandler\Translation\TranslationConfigurator;
use TYPO3\CMS\Core\Log\LogLevel;

class Common implements ConfigureTranslationInterface,
                        ConfigureTypoCoreInterface,
                        ConfigureFluidInterface
{
    /**
     * @inheritDoc
     */
    public static function configureTranslation(TranslationConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->registerNamespace('exampleBe', 'locallang_be.xlf');
    }
    
    /**
     * @inheritDoc
     */
    public static function configureCore(TypoCoreConfigurator $configurator, ExtConfigContext $context): void
    {
        // Register a new log file writer for all logs written by this extension
        $configurator->registerFileLog(['key' => 'demoLog']);
        
        // Whenever one of the classes in your extension logs a "warning" it will automatically end up in the backend log overview.
        $configurator->registerBeLogLogger(['logLevel' => LogLevel::WARNING]);
        
        // Registers a global log writer that will push all logs into the stdOut of the application.
        // This makes this log writer extremely useful for docker environments.
        // "global" writers catch all log entries that have not been processed by specifically configured log writers
        $configurator->registerStreamLogger(['global']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureFluid(FluidConfigurator $configurator, ExtConfigContext $context): void
    {
        // This will automatically register all view-helpers in your \Vendor\ExtKey\ViewHelpers namespace
        // as ExtKey:Example into the fluid eco-system
        $configurator->registerViewHelpers();
        
    }
    
    
}
