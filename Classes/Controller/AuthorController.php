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


namespace LaborDigital\T3baExample\Controller;


use LaborDigital\T3ba\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3baExample\Domain\Model\Article\Author;
use LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository;
use TYPO3\CMS\Core\DependencyInjection\NotFoundException;

class AuthorController extends BetterContentActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface
{
    /**
     * @var \LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository
     */
    protected $articleRepository;
    
    /**
     * AuthorController constructor.
     *
     * @param   \LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository  $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    
    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('exampleBe.p.author.title')
                     ->setDescription('exampleBe.p.author.desc');
    }
    
    public function detailAction(?Author $author): void
    {
        if ($author === null) {
            throw new NotFoundException('You have to require an author for this action');
        }
        
        $this->view->assign('author', $author);
        $this->view->assign('articles', $this->articleRepository->findForAuthor($author));
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        // We tell our plugin to simply show the description, because without a selected
        // author we can't create a meaningful backend preview
        $context->setShowDescription();
    }
    
    
}
