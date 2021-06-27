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
 * Last modified: 2021.06.27 at 13:57
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Controller;


use LaborDigital\T3ba\ExtBase\Controller\BetterActionController;
use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3baExample\Domain\Model\BlogPost;
use LaborDigital\T3baExample\Domain\Repository\BlogPostRepository;

class BlogController extends BetterActionController implements ConfigurePluginInterface,
                                                               BackendPreviewRendererInterface
{
    /**
     * @var \LaborDigital\T3baExample\Domain\Repository\BlogPostRepository
     */
    protected $repository;
    
    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('exampleBe.p.blog.list.title')
                     ->setDescription('exampleBe.p.blog.list.desc')
                     ->setWizardTab('landingpage')
                     ->setWizardTabLabel('exampleBe.wizardTab.landingPage')
                     ->setActions(['list']);
        
        $configurator->getVariant('detail')
                     ->setTitle('exampleBe.p.blog.detail.title')
                     ->setDescription('exampleBe.p.blog.list.desc')
                     ->setWizardTab('landingpage')
                     ->setActions(['detail']);
    }
    
    public function listAction(): void
    {
        $this->view->assign('posts', $this->repository->getOrderedBlogPosts());
    }
    
    public function detailAction(?BlogPost $post = null)
    {
        if ($post === null) {
            return $this->handleNotFound(null, ['redirectToLink' => 'blogList']);
        }
        
        $this->view->assign('post', $post);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        // Alternatively to the examples in the ArticleController you don't need a full blown
        // fluid template for your backend preview if you only want to show a list of entries
        
        // In this example we just ignore the detail variant of the plugin
        if ($context->getVariant() === 'detail') {
            return null;
        }
        
        // And use the rendering utilities, provided by the context to render a list
        // of database records in a HTML table.
        return $context->getUtils()->renderRecordTable(
        // First we provide the name of the table, it's up to you how you specify it  here
        // You can use Extbase Model classes, T3BA table configuration classes
        // or the actual name of the table
            BlogPost::class,
            // Next we retrieve the list of database rows we want to render
            // Either as an array or as array of extbase domain models.
            $this->repository->getOrderedBlogPosts()->getQuery()->setLimit(5)->execute(true),
            // And define a list of database fields we want to render in our table
            ['title', 'author_name', 'date']
        );
    }
}