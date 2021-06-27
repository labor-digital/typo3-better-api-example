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


namespace LaborDigital\T3baExample\Domain\DataModel;


use LaborDigital\T3ba\Tool\Tca\ContentType\Domain\AbstractDataModel;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Button extends AbstractDataModel
{
    
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $icon;
    
    /**
     * @var string
     */
    protected $label;
    
    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getIcon(): ?FileReference
    {
        return $this->icon;
    }
    
    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }
}
