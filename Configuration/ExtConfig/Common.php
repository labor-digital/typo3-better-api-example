<?php
/*
 * Copyright 2020 LABOR.digital
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
 * Last modified: 2020.10.20 at 11:19
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Core\ConfigureTypoCoreInterface;
use LaborDigital\T3BA\ExtConfigHandler\Core\TypoCoreConfigurator;
use LaborDigital\T3BA\ExtConfigHandler\Fluid\ConfigureFluidInterface;
use LaborDigital\T3BA\ExtConfigHandler\Fluid\FluidConfigurator;
use LaborDigital\T3BA\ExtConfigHandler\Translation\ConfigureTranslationInterface;
use LaborDigital\T3BA\ExtConfigHandler\Translation\TranslationConfigurator;

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
