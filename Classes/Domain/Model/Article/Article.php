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
 * Last modified: 2021.02.12 at 21:20
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Domain\Model\Article;


use DateTime;
use LaborDigital\T3BA\ExtBase\Domain\Model\BetterEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Article extends BetterEntity
{
    
    /**
     * @var string
     */
    protected $headline;
    
    /**
     * @var string
     */
    protected $subHeadline;
    
    /**
     * @var string
     */
    protected $teaserText;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LaborDigital\Typo3BetterApiExample\Domain\Model\Article\Author>
     */
    protected $author;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LaborDigital\Typo3BetterApiExample\Domain\Model\Content>
     */
    protected $content;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Quote>
     */
    protected $quotes;
    
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $bannerImage;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;
    
    /**
     * @var \DateTime
     */
    protected $published;
    
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
    public function getSubHeadline(): ?string
    {
        return $this->subHeadline;
    }
    
    /**
     * @return string
     */
    public function getTeaserText(): string
    {
        return $this->teaserText;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getAuthor(): ?ObjectStorage
    {
        return $this->author;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getContent(): ?ObjectStorage
    {
        return $this->content;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getQuotes(): ?ObjectStorage
    {
        return $this->quotes;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getBannerImage(): ?FileReference
    {
        return $this->bannerImage;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }
    
    /**
     * @return \DateTime
     */
    public function getPublished(): ?DateTime
    {
        return $this->published;
    }
    
}
