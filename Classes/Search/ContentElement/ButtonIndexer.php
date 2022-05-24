<?php
/*
 * Copyright 2022 LABOR.digital
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
 * Last modified: 2022.04.13 at 14:13
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Search\ContentElement;


use LaborDigital\T3sai\Core\Indexer\Queue\QueueRequest;
use LaborDigital\T3sai\Search\Indexer\Page\ContentElement\AbstractContentElementIndexer;

class ButtonIndexer extends AbstractContentElementIndexer
{
    /**
     * @inheritDoc
     */
    public function canHandle(string $cType, string $listType, array $row, QueueRequest $request): bool
    {
        // This method is called on all registered content element indexers to check
        // if one of them can handle a certain element. If multiple indexers can handle
        // an element, the first available indexer will be used.
        // An indexer can handle multiple cTypes or listTypes if it fits your needs.
        // In this example we want an indexer explicitly for our example button
        return $cType === 't3baexample_button';
    }
    
    /**
     * @inheritDoc
     */
    public function generateContent(array $row, QueueRequest $request): string
    {
        // This method is used to stringify the database row of the content element.
        // The $row is already enriched with potential extension columns provided by the T3BA framework.
        
        // This example is fairly simple, because our button only has a label we want to use as searchable content.
        return $row['label'];
    }
    
}