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

use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Di\DefaultDiConfig;
use LaborDigital\T3baExample\Middleware\DemoMiddleware;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Class DiContainerConfig
 *
 * This configuration does seemingly nothing, but it extends the DefaultDependencyInjectionConfig
 * which sets up the auto-wiring of all extension classes in the symfony container for us.
 *
 * @package LaborDigital\T3baExample\Configuration\ExtConfig
 */
class DiContainer extends DefaultDiConfig
{
    /**
     * @inheritDoc
     */
    public static function configure(
        ContainerConfigurator $configurator,
        ContainerBuilder $containerBuilder,
        ExtConfigContext $context
    ): void
    {
        parent::configure($configurator, $containerBuilder, $context);
        
        // We want to pass some configuration to our demo middleware
        // This can be done using the dependency injection container configuration
        // For additional configuration we could also register a factory method here
        $containerBuilder->findDefinition(DemoMiddleware::class)
                         ->addArgument('Hello world! How are you today?')
                         ->addArgument('/say-hello');
    }
    
}
