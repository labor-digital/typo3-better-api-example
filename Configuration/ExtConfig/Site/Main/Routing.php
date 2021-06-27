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
 * Last modified: 2021.06.04 at 17:44
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Configuration\ExtConfig\Site\Main;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Routing\Site\ConfigureSiteRoutingInterface;
use LaborDigital\T3ba\ExtConfigHandler\Routing\Site\SiteRoutingConfigurator;
use LaborDigital\T3baExample\Configuration\Table\Article\ArticleTable;
use LaborDigital\T3baExample\Configuration\Table\Article\AuthorTable;
use LaborDigital\T3baExample\Controller\ArticleController;
use LaborDigital\T3baExample\Controller\AuthorController;

class Routing implements ConfigureSiteRoutingInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureSiteRouting(SiteRoutingConfigurator $configurator, SiteConfigContext $context): void
    {
        // Here we want to configure the route enhancers for our plugins.
        // Route enhancers are described in great detail here:
        // https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Routing/AdvancedRoutingConfiguration.html
        
        // By default, you have to configure your route enhancers in your site.yml file. Which means no auto-complete
        // for you and a lot of "copy-paste" work from all over the place. This configuration option tries to ease the
        // process of creating enhancers.
        
        // Before we begin, please note, that this option is "site based" meaning, different TYPO3 sites can have
        // different configuration classes. By default this class applies to ALL existing sites in your installation.
        // If you want to limit the configuration to specific sites you can use the "SiteKeyProviderInterface"
        // to define your constraints.
        
        // Site based options can be spotted easily, as they provide you with the "SiteConfigContext" instead of the
        // normal "ExtConfigContext".
        
        // Keep in mind, that, if your configuration applies to multiple sites it will be executed multiple times,
        // once for each site it applies to. Therefore you should keep it stateless.
        // You can always check which site gets currently configured using $context->getSite() or $context->getSiteKey()
        
        // As you have seen we have our article list plugin on the root page of our installation.
        // In that plugin we use the fluid pagination widget. As the plugin has no arguments other
        // than the pagination, we can use the registerExtBasePagination() configurator to create a route
        // that will create a url like /1, /2 or /3 depending on the currently selected page.
        $configurator->registerExtBasePagination(
        // First we define a name for our enhancer. The name can be chosen freely, but must be unique in your site configuration.
            'articlePagination',
            // Next, we define the controller class, as well as the action name we want to create a pagination for.
            // Internally we will use the "Extbase" route enhancer and inflect the plugin name automatically for you.
            ArticleController::class, 'list',
            // An as a last step, we provide a list, or in this case a single pid on which this enhancer should be active.
            // You can either use numeric values, or pid references here.
            ['@pid.page.news.list']
        );
        
        // The second route is used on our article detail page (/article/)
        // Its job is to map the last url segment against the article-tables "slug" field.
        // TYPO3 calls this process a "persisted alias mapping". If you work with non-extbase content elements
        // you would use the registerValueRoute() method for that process, but because we DO work with extbase,
        // we use registerExtbasePlugin() instead.
        $configurator->registerExtbasePlugin(
        // This is, again a unique name for our enhancer
            'newsDetail',
            // This is the route path we want to register, it can contain either static strings, or dynamic
            // segments (article in our case) to be mapped.
            '/{article}',
            // Similar to before, we define the extbase controller and our action name...
            ArticleController::class, 'detail',
            // ... and define the pids where our enhancer should be active
            ['@pid.page.news.detail'],
            [
                // We use the "dbArgs" option to tell TYPO3 that a specific segment should be resolved
                // using a database table field. In our case, we map the segment "article" on the "slug" field
                // of our article table.
                // You could either put in the table name, or, like we do it here, use the table configuration
                // class as a reference. The internal logic will resolve the class name into the real table name for you.
                'dbArgs' => [
                    'article' => [ArticleTable::class, 'slug'],
                ],
            ]
        );
        
        // Finally we want to configure the routes for our author detail page.
        $configurator->registerExtbasePlugin(
            'authorDetail',
            '{author}',
            AuthorController::class, 'detail',
            ['@pid.page.news.author.detail'],
            [
                // This is special, because our author-detail page contains a list of all
                // articles of the shown author, we have a pagination of those articles inside
                // the "detail" view. Therefore we create an "additional" route, which acts as a
                // child of our main route.
                //
                // {pageSegment} is a statically mapped value, that should be aware of the current language of the page
                // therefore we map it using the "localeArgs" option, below.
                //
                // {page} is the "magic" keyword here. Any "additional" route, that contains the {page} segment, will automatically
                // be configured as a pagination route. This will also take care of the mapping to the correct fluid pagination widget for you.
                // See the documentation of registerExtbasePlugin() for additional information about the "additional" option (*cough* additional...).
                'additional' => [
                    '{pageSegment}/{page}',
                ],
                
                'dbArgs' => [
                    'author' => [AuthorTable::class, 'slug'],
                ],
                
                // Sometimes you want to translate a static segment of your route based on the current language.
                // To do that you register it as "localeArgs" and tell the configurator to create a "LocaleModifier" for you.
                'localeArgs' => [
                    // The first value (numeric index!) is used as the default value,
                    // All other values should be mapped with their matching locale to their corresponding value
                    'pageSegment' => ['article-page', 'de_DE.*' => 'artikel-seite'],
                ],
            ]
        );
        
    }
    
}
