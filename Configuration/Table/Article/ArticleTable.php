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


namespace LaborDigital\T3baExample\Configuration\Table\Article;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;

class ArticleTable implements ConfigureTcaTableInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->setTitle('exampleBe.t.article.title');
        
        // Make sure this table always is shown in front of "tt_content" records
        $table->setListPosition('tt_content');
        
        $table->setLabelColumn('headline');
        
        $type = $table->getType();
        
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('headline')
                 ->setLabel('exampleBe.t.article.field.headline')
                 ->applyPreset()->input(['required']);
            
            $type->getField('sub_headline')
                 ->setLabel('exampleBe.t.article.field.subHeadline')
                 ->applyPreset()->input();
            
            // To create our own route enhancer that reads a link for this article,
            // we have to create a slug field. We create it and let it generate the content based
            // on the headline field
            $type->getField('slug')
                 ->setLabel('exampleBe.t.article.field.slug')
                 ->applyPreset()->slug(['headline'], ['prefix' => '/article/']);
            
            // In our list action we want to show a short teaser text to get the attention of a user
            $type->getField('teaser_text')
                 ->setLabel('exampleBe.t.article.field.teaserText')
                // Since TYPO3 v9 it is possible to provide descriptions for the editor in the
                // backend. They are registered like a field label.
                 ->setDescription('exampleBe.t.article.field.teaserText.desc')
                 ->applyPreset()->textArea(['maxLength' => 512]);
            
            // We want to define a list of authors for our article. To define the relation we could either
            // use the selectGroup() preset to do that. However, we created a custom preset
            // to select an author in the following file:
            // \LaborDigital\T3baExample\FormEngine\FieldPreset\Author::applySelectAuthor()
            // and therefore we can use it here directly.
            $type->getField('author')
                 ->setLabel('exampleBe.t.article.field.author')
                 ->applyPreset()->selectAuthor(['required', 'maxItems' => 4]);
            
            // We want our article to have a timestamp that show's when it was published.
            // So we use a date preset and we also allow the selection of a time using the "withTime" flag.
            $type->getField('published')
                 ->setLabel('exampleBe.t.article.field.published')
                 ->applyPreset()->date(['withTime', 'required']);
        });
        
        // We create a new tab that is sorted after "general" that holds our content options for this article
        // We store it in a variable, because we need it again later as a reference when we add additional tabs
        $contentTab = $type->getNewTab()
                           ->moveTo('0')
                           ->setLabel('exampleBe.t.article.tab.content');
        
        $contentTab->addMultiple(static function () use ($type) {
            // We create a new field that can hold content elements as IRRE field
            $type->getField('content')
                 ->setLabel('exampleBe.t.article.field.content')
                 ->applyPreset()->inlineContent();
            
            // We also want a list of quotes from other authors
            $type->getField('quotes')
                 ->setLabel('exampleBe.t.article.field.quotes')
                // As you can see here, we can simply use the table definition class of "quote" as
                // table name. The internal logic will resolve the correct table name from that.
                 ->applyPreset()->inline(QuoteTable::class);
        });
        
        // We want to create
        $type->getNewTab()
             ->moveTo((string)$contentTab->getId())
             ->setLabel('exampleBe.t.article.tab.relations')
             ->addMultiple(static function () use ($type) {
                 // We want to add a banner image for our article, we do this using the file abstraction layer (FAL)
                 // In this case we only want a single image
                 $type->getField('banner_image')
                      ->setLabel('exampleBe.t.article.field.bannerImage')
                      ->applyPreset()->relationImage([
                         'maxItems' => 1,
                         // We don't want either the "description" or the "link" field to be
                         // shown in the fal field, so we can remove them using the options
                         'disableFalFields' => ['description', 'link'],
                         // We also want some specific crop formats that can be used.
                         // You can use either the TYPO3 default definition,
                         // or this simplified definition to create your crop variants
                         'cropVariants' => [
                             [
                                 'title' => 'exampleBe.t.article.field.bannerImage',
                                 'aspectRatios' => [
                                     '16:9' => 'exampleBe.t.article.field.bannerImage.crop.wide',
                                     '1:1' => 'exampleBe.t.article.field.bannerImage.crop.quadratic',
                                 ],
                             ],
                         ],
                     ]);
            
                 // Now we want to add some categories to our article,
                 // we can do this using TYPO3's categorization system
                 $type->getField('categories')
                      ->setLabel('exampleBe.t.article.field.categories')
                     // Yes, this is everything you need to create a category field
                     // Note: By default only categories that are stored in the same folder/page are used
                     // you can modify this by using the "limitToPids" option.
                     //
                     // We use "sideBySide" because of personal preference. You don't need this flag
                     // if you want to render a normal tree instead
                      ->applyPreset()->categorize(['sideBySide']);
            
             });
        
    }
    
}
