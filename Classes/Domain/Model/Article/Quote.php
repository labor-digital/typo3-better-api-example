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


namespace LaborDigital\T3baExample\Domain\Model\Article;


use LaborDigital\T3ba\ExtBase\Domain\Model\BetterEntity;

class Quote extends BetterEntity
{
    
    /**
     * @var string|null
     */
    protected $quote;
    
    /**
     * @var \LaborDigital\T3baExample\Domain\Model\Article\Author|null
     */
    protected $author;
    
    /**
     * @return string
     */
    public function getQuote(): string
    {
        return (string)$this->quote;
    }
    
    /**
     * @return \LaborDigital\T3baExample\Domain\Model\Article\Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }
}
