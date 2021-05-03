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
use LaborDigital\T3ba\Tool\DataHook\DataHookTypes;
use LaborDigital\T3baExample\Domain\Model\Article\Article;
use LaborDigital\T3baExample\Domain\Repository\Article\ArticleRepository;
use LaborDigital\T3baExample\EventHandler\DataHook\ButtonDataHook;
use TYPO3\CMS\Core\DependencyInjection\NotFoundException;

class ArticleController extends BetterContentActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface
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
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        // We want to make it easy for the editor to find our new plugin,
        // therefore we want to give it a speaking name and a description.
        $configurator->setTitle('exampleBe.p.article.title')
                     ->setDescription('exampleBe.p.article.desc');
        
        // By default all actions will be used in order of their definition inside the controller.
        // However, because we want to create a plugin with multiple "variants"
        // (look further down for more information on that topic) we want to limit the usage to the
        // list action only.
        $configurator->setActions(['list']);
        
        // Similar to a TCA table, you can access the flex form configuration
        // of your plugin using an object oriented way. In this case we start with an empty form and
        // create the fields we require.
        $flex = $configurator->getFlexForm();
        
        // We want to let the editor decide how the articles should be ordered.
        // We do this by letting them chose a field and the sorting direction
        $flex->getField('settings.sorting')
             ->setLabel('exampleBe.p.article.field.sorting')
             ->applyPreset()->select([
                'headline' => 'exampleBe.t.article.field.headline',
                'published' => 'exampleBe.t.article.field.published',
            ]);
        $flex->getField('settings.direction')
             ->registerDataHook(DataHookTypes::TYPE_FORM, ButtonDataHook::class)
             ->setLabel('exampleBe.p.article.field.direction')
             ->applyPreset()->select([
                'asc' => 'exampleBe.p.article.field.direction.asc',
                'desc' => 'exampleBe.p.article.field.direction.desc',
            ]);
        
        // Now, because switchable controller actions are deprecated since TYPO3 v10
        // we should migrate to using multiple plugins and register the same controller for them:
        // https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Feature-91008-ItemGroupingForTCASelectItems.html#changelog-feature-91008-itemgroupingfortcaselectitems
        // To do this we want to register a new "variant" of this plugin that contains our "detail" view.
        // This is fairly simple, just require it from the configurator. Just keep in mind, that each variant has
        // to be given a unique name. I would suggest using the action name as a variant name. Because, if we name or
        // variant "detail" the internal logic will automatically check if there is a "detailAction" method and register
        // it as action for us.
        $variant = $configurator->getVariant('detail');
        
        // Now, as well as our normal implementation the variant will show up in the "new content element wizard".
        // Therefore we define a title and description for it as well.
        $variant->setTitle('exampleBe.p.article.detail.title')
                ->setDescription('exampleBe.p.article.detail.desc');
    }
    
    public function listAction()
    {
        // We use our article repository to retrieve a list of all articles to show.
        $this->view->assign('articles', $this->repository->findForListPlugin($this->settings, $this->getData()));
    }
    
    public function detailAction(?Article $article)
    {
        if ($article === null) {
            throw new NotFoundException('You have to require an article for this action');
        }
        
        $this->view->assign('article', $article);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        // Whenever you use BetterContentActionController you automatically have access to the
        // ContentControllerBackendPreviewTrait trait, which will also work on its own for every extbase controller
        // out of the box and provide you with some additional features.
        
        // Your first option is to load a fluid view directly in this method
        // and provide it with the required arguments. To do that simply use the following getFluidView().
        // Note: The method tries to find a BackendPreview.html template inside the template directory of your plugin.
        // Example:
        // return $this->getFluidView();
        
        // Or in our case we want to do exactly the same logic than the frontend controller does.
        // If you have a simple plugin you can do this by simulating a frontend request in the backend.
        // The ExtBaseBackendPreviewRendererTrait gives you a helper for that, which is called simulateRequest().
        // You must simply provide it with a action name that should be rendered, and it will do that for you.
        // Note: Like getFluidView() the method will use a BackendPreview.html template instead of the template
        // used for the frontend.
        // Example: render the list action using the BackendPreview.html
        // return $this->simulateRequest('list');
        
        // However, in our case we work with multiple "variants" of the same plugin.
        // Therefore we want to be aware of the used variant and use the matching method accordingly.
        // ExtBaseBackendPreviewRendererTrait has a helper for that as well, it is called simulateVariantRequest().
        // It works quite similar to simulateRequest() but instead of a single action name it receives a map of
        // "variant" => "actionName" as argument.
        // Note1: To map the action for the default plugin configuration simply use the "default" key.
        // Note2: Because we work with multiple variants we probably want to use multiple templates as well.
        // The method automatically picks up the "BackendPreview" naming schema, but attaches the name of the variant
        // to it. So our "detail" variant will look for a BackendPreviewDetail.html inside the templates directory,
        // while the "default" variant, will still look for the BackendPreview.html
        // Example:
        return $this->simulateVariantRequest([
            'default' => 'list',
            
            // The detail action, requires an $article to be set (done by our route enhancer),
            // otherwise it will throw a not found exception. For a backend preview, of a detail page
            // it does not make sense to map a static article, therefore we disable the request by
            // setting the detail variant to false.
            'detail' => false,
        ]);
    }
    
}
