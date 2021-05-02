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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Content extends BetterEntity
{
    
    /**
     * @var string
     */
    protected $headline;
    
    /**
     * @var string
     */
    protected $bodytext;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $media;
    
    /**
     * @return string
     */
    public function getHeadline(): ?string
    {
        return $this->headline;
    }
    
    /**
     * @return string
     */
    public function getBodytext(): ?string
    {
        return $this->bodytext;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMedia(): ?ObjectStorage
    {
        return $this->media;
    }
}
