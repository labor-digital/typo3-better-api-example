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
 * Last modified: 2022.04.11 at 15:54
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Configuration\ExtConfig\Site\Main;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3baExample\Search\ContentElement\ButtonIndexer;
use LaborDigital\T3baExample\Search\Record\ArticleIndexer;
use LaborDigital\T3baExample\Search\Record\AuthorIndexer;
use LaborDigital\T3sai\ExtConfigHandler\Domain\ConfigureSearchDomainInterface;
use LaborDigital\T3sai\ExtConfigHandler\Domain\DomainConfigurator;
use LaborDigital\T3sai\Search\Indexer\Page\PageIndexer;

class SearchAndIndex implements ConfigureSearchDomainInterface
{
    /**
     * @inheritDoc
     */
    public static function getDomainIdentifier(): string
    {
        // This configuration file applies to a single search domain.
        // A "domain" is basically a namespace that is bound to a specific TYPO3 site.
        // Each site can have multiple domains, each with their own configuration.
        return 'main';
    }
    
    /**
     * @inheritDoc
     */
    public static function configureSearchDomain(DomainConfigurator $configurator, ExtConfigContext $context): void
    {
        // You can limit the languages taken into account by defining which languages are allowed.
        // It this option is not set, ALL langauges will be indexed.
        $configurator->setAllowedLanguages(['en']);
        
        // Record indexers are used to convert TYPO3 records into index nodes.
        $configurator->getRecordIndexers()
            // The Page indexer traverses the page tree and adds them to the index.
            // It is builtin to T3SAI and can be used out of the box.
                     ->add(PageIndexer::class,
                [
                    // Each indexer can receive a list of options to configure how it works
                    // in a specific domain. Check the indexer class to see which options are supported
                ])
            // These are indexers, implemented by the example extension...
                     ->add(AuthorIndexer::class)
                     ->add(ArticleIndexer::class);
        
        // When indexing pages only the most basic fields (header, sub_header and bodytext)
        // are indexed, to tailor the indexer to fit your implementations,
        // you can register special indexers for your elements.
        $configurator->getContentElementIndexers()
                     ->add(ButtonIndexer::class);
        
        // To render the tags provided by our indexers nicely to the screen, we can define some translation labels for them
        $configurator->addTagTranslation('author', 'example.misc.search.tag.author')
                     ->addTagTranslation('article', 'example.misc.search.tag.article');
    }
    
}