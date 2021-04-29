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


namespace LaborDigital\Typo3BetterApiExample\Domain\Repository\Article;


use LaborDigital\T3BA\ExtBase\Domain\Repository\BetterRepository;
use LaborDigital\T3BA\Tool\Database\BetterQuery\AbstractBetterQuery;
use LaborDigital\Typo3BetterApiExample\Domain\Model\Article\Author;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class ArticleRepository extends BetterRepository
{
    
    /**
     * Returns the list of all articles that can be found
     *
     * @param   array       $settings
     * @param   array|null  $row
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findForListPlugin(array $settings, ?array $row = null): QueryResultInterface
    {
        // Why would you want to pass the $row, you ask? Because if you look closely it contains a field, called "pages".
        // That field defines the "record storage pages", or the place where the editor told TYPO3 to look for records.
        // The better query will take a look at that field and automatically create the required pid constraints for you.
        return $this->getPreparedQuery($settings, $row)->getAll();
    }
    
    /**
     * Returns a list of all articles by this author
     *
     * @param   \LaborDigital\Typo3BetterApiExample\Domain\Model\Article\Author  $author
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findForAuthor(Author $author): QueryResultInterface
    {
        return $this->getQuery()->withWhere(['author has' => $author])->withOrder('published', 'desc')->getAll();
    }
    
    /**
     * This method is not required but it is a nice example on how you can use the ext base settings
     * to pre-configure a query object based on global rules.
     *
     * Keep in mind that better query is a immutable object, so you receive a new instance every time you
     * change one of its properties.
     *
     * @inheritDoc
     */
    protected function prepareBetterQuery(AbstractBetterQuery $query, array $settings, array $row): AbstractBetterQuery
    {
        // Check if we got a sorting setting and apply it to the query if we do.
        if ($settings['sorting']) {
            $query = $query->withOrder($settings['sorting'], $settings['direction'] ?? 'desc');
        }
        
        return $query;
    }
}
