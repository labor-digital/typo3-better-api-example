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
 * Last modified: 2020.10.19 at 20:36
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig;

use LaborDigital\T3BA\ExtConfigHandler\DependencyInjection\DefaultDependencyInjectionConfig;

/**
 * Class DiContainerConfig
 *
 * This configuration does seemingly nothing, but it extends the DefaultDependencyInjectionConfig
 * which sets up the auto-wiring of all extension classes in the symfony container for us.
 *
 * @package LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig
 */
class DiContainerConfig extends DefaultDependencyInjectionConfig
{
}
