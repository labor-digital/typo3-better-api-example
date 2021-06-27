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
 * Last modified: 2021.06.27 at 13:54
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Domain\Model;


use LaborDigital\T3ba\ExtBase\Domain\Model\BetterEntity;

class BlogPost extends BetterEntity
{
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $bodyText;
    
    /**
     * @var \DateTime|null
     */
    protected $date;
    
    /**
     * @var string|null
     */
    protected $authorName;
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * @param   string  $title
     *
     * @return BlogPost
     */
    public function setTitle(string $title): BlogPost
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBodyText(): string
    {
        return $this->bodyText;
    }
    
    /**
     * @param   string  $bodyText
     *
     * @return BlogPost
     */
    public function setBodyText(string $bodyText): BlogPost
    {
        $this->bodyText = $bodyText;
        
        return $this;
    }
    
    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }
    
    /**
     * @param   \DateTime|null  $date
     *
     * @return BlogPost
     */
    public function setDate(?\DateTime $date): BlogPost
    {
        $this->date = $date;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }
    
    /**
     * @param   string|null  $authorName
     *
     * @return BlogPost
     */
    public function setAuthorName(?string $authorName): BlogPost
    {
        $this->authorName = $authorName;
        
        return $this;
    }
}