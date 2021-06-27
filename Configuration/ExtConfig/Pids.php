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
use LaborDigital\T3ba\ExtConfigHandler\Pid\ConfigurePidsInterface;
use LaborDigital\T3ba\ExtConfigHandler\Pid\PidCollector;

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
                'news' => [
                    'article' => 4,
                    'author' => 5,
                ],
                'blogPost' => 11,
            ],
            'page' => [
                'news' => [
                    'list' => 1,
                    'detail' => 6,
                    'author' => [
                        'detail' => 7,
                    ],
                ],
                'blog' => [
                    'list' => 10,
                    'detail' => 13,
                ],
            ],
        ]);
        
    }
    
}
