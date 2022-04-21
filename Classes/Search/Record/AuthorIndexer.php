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
 * Last modified: 2022.04.11 at 15:56
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Search\Record;


use LaborDigital\T3baExample\Domain\Model\Article\Author;
use LaborDigital\T3baExample\Domain\Repository\Article\AuthorRepository;
use LaborDigital\T3sai\Core\Indexer\Node\Node;
use LaborDigital\T3sai\Core\Indexer\Queue\QueueRequest;
use LaborDigital\T3sai\Search\Indexer\AbstractRecordIndexer;

class AuthorIndexer extends AbstractRecordIndexer
{
    /**
     * @var \LaborDigital\T3baExample\Domain\Repository\Article\AuthorRepository
     */
    protected $repository;
    
    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve(QueueRequest $request): iterable
    {
        return $this->repository->getQuery()->getAll();
    }
    
    /**
     * @inheritDoc
     */
    public function index($element, Node $node, QueueRequest $request): void
    {
        if (! $element instanceof Author) {
            return;
        }
        
        $node->setTag('author');
        $node->setTitle($element->getFirstName() . ' ' . $element->getLastName());
        $node->setLink('authorDetail', ['author' => $element]);
        
        // The base priority of authors is set quite high, because we want our users to find them immediately,
        // even if they don't have a lot of content themselves
        $node->setPriority(80);
    }
    
}