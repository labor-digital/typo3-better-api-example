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
 * Last modified: 2020.09.04 at 16:40
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Pid\ConfigurePidsInterface;
use LaborDigital\T3BA\ExtConfigHandler\Pid\PidCollector;

class Pids implements ConfigurePidsInterface
{
    /**
     * @inheritDoc
     */
    public static function configurePids(PidCollector $collector, ExtConfigContext $context): void
    {
        $collector->set('page.home', 1);
        
        $collector->setMultiple([
            'storage' => [
                'article' => 5,
                // Currently we store authors in the same folder like articles,
                // but changing that would be trivial in the future. Move the records an adjust the
                // pid here once and you are done.
                'author' => 5,
            ],
            'page' => [
                'article' => [
                    'list' => 1,
                    'detail' => 6,
                ],
                'author' => [
                    'detail' => 7,
                ],
            ],
        ]);
        
    }
    
}
