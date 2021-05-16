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
use LaborDigital\T3ba\ExtConfigHandler\Routing\ConfigureRoutingInterface;
use LaborDigital\T3ba\ExtConfigHandler\Routing\RoutingConfigurator;
use LaborDigital\T3baExample\Middleware\DemoMiddleware;

class Routing implements ConfigureRoutingInterface
{
    /**
     * @inheritDoc
     */
    public static function configureRouting(RoutingConfigurator $configurator, ExtConfigContext $context): void
    {
        // Registering a new middleware into the dispatcher stack of TYPO3 is really easy with the http configuration.
        $configurator->registerMiddleware(
        // Just tell the script which middleware class you want to configure, by default this script assumes
        // that you want to register a frontend middleware, but you can also register one for the backend using the "stack" option.
            DemoMiddleware::class,
            [
                // We tell TYPO3 that our middleware should be called before it handles eid actions,
                // because our middleware is so simple, that it does not need additional boot steps to occur.
                // "before" and "after" can be either strings, or arrays of strings to define reference points.
                'before' => 'typo3/cms-frontend/eid',
            ]
        );
    }
    
}
