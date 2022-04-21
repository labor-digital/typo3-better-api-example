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
 * Last modified: 2022.04.13 at 13:43
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Search\Record;


use LaborDigital\T3baExample\Domain\Model\Article\Article;
use LaborDigital\T3baExample\Domain\Model\Article\Quote;
use LaborDigital\T3baExample\Domain\Model\Content;
use LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository;
use LaborDigital\T3sai\Core\Indexer\Node\Node;
use LaborDigital\T3sai\Core\Indexer\Queue\QueueRequest;
use LaborDigital\T3sai\Search\Indexer\AbstractRecordIndexer;

class ArticleIndexer extends AbstractRecordIndexer
{
    /**
     * @var \LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository
     */
    protected $repository;
    
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve(QueueRequest $request): iterable
    {
        // The resolve method is used to find the list (preferably a lazy loading iterator)
        // of elements that should be added to the index. Each item will then be passed to the
        // index() method, which should map it to the provided node object.
        
        // This is a super simple example on how to find entries,
        // however you can use any repository method that returns a query result as well.
        
        // Note: The indexing runs in multiple passes for multiple sites, languages and search domains
        // this means your indexer might run multiple times, so ensure it stays stateless!
        return $this->repository->getQuery()->getAll();
    }
    
    /**
     * @inheritDoc
     */
    public function index($element, Node $node, QueueRequest $request): void
    {
        // This method is called for every item which was returned by the resolve() method.
        // The $element is said item and should be mapped into the $node object by this method.
        
        if (! $element instanceof Article) {
            // This disables the processing of the node if somehow we would not get an article object
            // Not really necessary, but it is nice as an example ;)
            $node->setAddToSearchResults(false);
            
            return;
        }
        
        // The search is faceted, means you can search specific media-types in your query
        // therefore it is recommended to provide a tag for each node you are indexing.
        // Note, every node can only have a single tag
        $node->setTag('article')
            // You have multiple ways on configuring a link to the element.
            // Here you see, how we use the link set configuration provided by T3BA extension
            // to reuse a preconfigured link with the element as article.
            // You can also use setHardLink() to provide an ABSOLUTE link to the node
            // alternatively you can use editLink() to create a custom link definition through the
            // T3BA link builder api
             ->setLink('newsDetail', ['article' => $element])
            // The title of an element is ALWAYS REQUIRED, all other properties (except "link") are optional.
             ->setTitle($element->getHeadline())
            // An optional description that will show up in the search results.
            // This is only used if the "showContentPreview" option is not set
            // @todo update definition name
             ->setDescription($element->getTeaserText())
            // The addContent method appends text content (that might be HTML code)
            // to the node. It can be called multiple times. Each time a new "block" is registered,
            // which has its own priority setting applied.
             ->addContent($element->getSubHeadline(), 50)
            // The timestamp of the element you are indexing. If set it is used to calculate the
            // node priority. Newer nodes have a higher priority -> older nodes a lower priority
             ->setTimestamp($element->getPublished())
            // Each node can have an image applied to it. Only a SINGLE Image can be connected,
            // even if a potential set (ObjectStorage, array...) of images is provided.
            // Note: You can also use external images by providing an absolute link
             ->setImage($element->getBannerImage());
        
        // We add the name of the author as "keywords".
        // Keywords are not visible to the user searching for content, but are taken into account
        // when the search is executed in the database. With this we can
        // add the author of the article and ensure that the articles show up when searching for the author
        if ($element->getAuthor()) {
            foreach ($element->getAuthor() as $author) {
                $node->addKeywords($author->getFirstName() . ' ' . $author->getLastName(), 80);
            }
        }
        
        // We iterate the article contents and add their content to the node
        foreach ($element->getContent() ?? [] as $content) {
            if (! $content instanceof Content) {
                continue;
            }
            
            $node->addContent($content->getHeadline())
                 ->addContent($content->getBodytext());
        }
        
        // Similar to the "contents" we iterate the quotes and add them to the node content.
        foreach ($element->getQuotes() ?? [] as $quote) {
            if (! $quote instanceof Quote) {
                continue;
            }
            
            $node->addContent($quote->getQuote(), -5);
            
            if ($quote->getAuthor()) {
                $node->addContent($quote->getAuthor()->getFirstName() . ' ' . $quote->getAuthor()->getLastName());
            }
        }
        
    }
    
}