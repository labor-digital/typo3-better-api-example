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
 * Last modified: 2021.02.15 at 17:57
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Controller;


use LaborDigital\T3BA\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\Typo3BetterApiExample\Domain\Model\Article\Author;
use LaborDigital\Typo3BetterApiExample\Domain\Repository\Article\ArticleRepository;
use TYPO3\CMS\Core\DependencyInjection\NotFoundException;

class AuthorController extends BetterContentActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface
{
    /**
     * @var \LaborDigital\Typo3BetterApiExample\Domain\Repository\Article\ArticleRepository
     */
    protected $articleRepository;
    
    /**
     * AuthorController constructor.
     *
     * @param   \LaborDigital\Typo3BetterApiExample\Domain\Repository\Article\ArticleRepository  $articleRepository
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
