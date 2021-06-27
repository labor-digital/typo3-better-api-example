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
 * Last modified: 2021.06.27 at 12:34
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Configuration\ExtConfig\Site\Common;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Frontend\ConfigureFrontendInterface;
use LaborDigital\T3ba\ExtConfigHandler\Frontend\FrontendConfigurator;

class Frontend implements ConfigureFrontendInterface
{
    /**
     * @inheritDoc
     */
    public static function configureFrontend(FrontendConfigurator $configurator, SiteConfigContext $context): void
    {
        // Similar to ConfigureRoutingInterface, ConfigureFrontendInterface is "site-based".
        // For additional information about that take a look here:
        // typo3-better-api-example/Configuration/ExtConfig/Routing.php:46
        
        // Just like for the backend, you can register your own assets using extConfig
        $configurator->registerStyleSheet('example-frontend-main', 'EXT:{{extKey}}/Resources/Public/Assets/frontend.css');
    }
}