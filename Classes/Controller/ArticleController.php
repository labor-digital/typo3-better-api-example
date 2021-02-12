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
 * Last modified: 2021.02.12 at 20:30
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Controller;


use LaborDigital\T3BA\ExtBase\Controller\BetterActionController;
use LaborDigital\T3BA\ExtBase\Controller\ExtBaseBackendPreviewRendererTrait;
use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\Typo3BetterApiExample\Domain\Repository\Article\ArticleRepository;

class ArticleController extends BetterActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface
{
    use ExtBaseBackendPreviewRendererTrait;

    /**
     * @var \LaborDigital\Typo3BetterApiExample\Domain\Repository\Article\ArticleRepository
     */
    protected $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('exampleBe.p.article.title')
                     ->setDescription('exampleBe.p.article.desc');

        // Similar to a TCA table, you can access the flex form configuration
        // of your plugin using an object oriented way. In this case we start with an empty form and
        // create the fields we require.
        $flex = $configurator->getFlexForm();

        // We want to let the editor decide how the articles should be ordered.
        // We do this by letting them chose a field and the sorting direction
        $flex->getField('settings.sorting')
             ->setLabel('exampleBe.p.article.field.sorting')
             ->applyPreset()->select([
                'headline'  => 'exampleBe.t.article.field.headline',
                'published' => 'exampleBe.t.article.field.published',
            ]);
        $flex->getField('settings.direction')
             ->setLabel('exampleBe.p.article.field.direction')
             ->applyPreset()->select([
                'asc'  => 'exampleBe.p.article.field.direction.asc',
                'desc' => 'exampleBe.p.article.field.direction.desc',
            ]);
    }

    public function listAction()
    {
//        dbge($this->repository->getPluginList($this->settings)->toArray());
        return get_class($this->view);

        return 'article list';
    }

    public function detailAction()
    {
        return 'detail list';
    }

    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return $this->simulateRequest('list');
    }

}
