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


namespace LaborDigital\T3baExample\Configuration\ExtConfig;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Link\ConfigureLinksInterface;
use LaborDigital\T3BA\ExtConfigHandler\Link\DefinitionCollector;
use LaborDigital\T3baExample\Configuration\Table\Article\ArticleTable;
use LaborDigital\T3baExample\Controller\ArticleController;
use LaborDigital\T3baExample\Controller\AuthorController;

class Links implements ConfigureLinksInterface
{
    /**
     * @inheritDoc
     */
    public static function configureLinks(DefinitionCollector $collector, ExtConfigContext $context)
    {
        // The first link we want to register is easy; we want a static link that always points to our homepage
        // For that we get a new definition and call it "home".
        $collector->getDefinition('home')
            // That definition should point to a pid which is defined in the Pids class (in this directory).
            // You could also simply put in a numeric pid here, but using the global reference index
            // of pids it becomes trivial to change pids on a later date.
                  ->setPid('@pid.page.home');
        
        // To register a new link set, we simply require one using getSet() from the collector
        // the syntax is quite similar to the T3BA TypoLink context, however, the collector is mutable,
        // so you can simply chain the methods like so:
        $collector->getDefinition('articleDetail')
            // We define the page id were our article detail plugin is stored.
            // You can either use a hard coded value, or reference a pid configured in Pids.
                  ->setPid('@pid.page.article.detail')
            // To define the arguments for a link you can either define one, or
            // create a placeholder that has to be provided when the link is build.
            // A placeholder is simply a string with a question mark as content.
                  ->addToArgs('article', '?')
            // We have to tell our link, that we are working with an extBase controller here.
            // You have multiple options here, but the simplest option is to provide the controller class
            // you want to handle your link
                  ->setControllerClass(ArticleController::class)
            // In addition to the class we also want to point our link to a certain action.
                  ->setControllerAction('detail')
            // We also want to allow the editor to directly link to an article
            // using the TYPO3 link selector. Therefore we tell the collector
            // to register a new link entry for us.
            // To do that, we provide a visible label, and the database table,
            // that should be used to resolve our "article" argument we defined above.
            // IMPORTANT: you can only register links to the link browser if they
            // require EXACTLY ONE argument. Neither 0, nor 2 or 22 will work!
                  ->addToLinkBrowser(
                'exampleBe.t.article.title',
                ArticleTable::class,
                [
                    // This is optional, but we want the TYPO3 backend to automatically
                    // open the correct storage pid for us when a user clicks on the "article" tab.
                    // Therefore we provide a base/storage pid using a reference in our pids
                    'basePid' => '@pid.storage.article',
                ]
            );
        
        // Next we register a simple definition that will always point to our article list pid
        $collector->getDefinition('articleList')
                  ->setPid('@pid.page.article.list');
        
        // Similar to the article detail we register a definition for an author detail page
        $collector->getDefinition('authorDetail')
                  ->setPid('@pid.page.author.detail')
                  ->addToArgs('author', '?')
                  ->setControllerClass(AuthorController::class)
                  ->setControllerAction('detail');
    }
    
}
