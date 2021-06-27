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
 * Last modified: 2021.06.27 at 13:55
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Domain\Repository;


use LaborDigital\T3ba\ExtBase\Domain\Repository\BetterRepository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class BlogPostRepository extends BetterRepository
{
    /**
     * Gets the list of blog posts, ordered by either their date or the tstamp value
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getOrderedBlogPosts(): QueryResultInterface
    {
        return $this->getQuery()->withOrder(['date' => 'desc', 'tstamp' => 'desc'])->getAll();
    }
}