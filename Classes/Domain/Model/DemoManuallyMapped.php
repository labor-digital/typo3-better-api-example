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


namespace LaborDigital\T3baExample\Domain\Model;


use LaborDigital\T3BA\ExtBase\Domain\Model\BetterEntity;

/**
 * Class DemoManuallyMapped
 *
 * A manually mapped domain model that does not conform with TYPO3s naming schema
 *
 * @package LaborDigital\T3baExample\Domain\Model
 *
 * @see     \LaborDigital\T3baExample\Configuration\Table\DemoTable
 */
class DemoManuallyMapped extends BetterEntity
{
    /**
     * @var string
     */
    protected $mappedInput;
    
    /**
     * @return string
     */
    public function getMappedInput(): string
    {
        return $this->mappedInput;
    }
}
