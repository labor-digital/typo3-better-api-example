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
 * Last modified: 2021.06.27 at 12:38
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\Configuration\Table;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;

class BlogPostTable implements ConfigureTcaTableInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->setTitle('exampleBe.t.blogPost.title')
              ->setLabelColumn('title')
              ->setSearchColumns(['title', 'body_text', 'author_name']);
        
        $type = $table->getType();
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('title')
                 ->setLabel('exampleBe.t.blogPost.field.title')
                 ->applyPreset()->input(['required']);
            
            $type->getField('slug')
                 ->setLabel('exampleBe.t.blogPost.field.slug')
                 ->applyPreset()->slug(['title']);
            
            $type->getPalette('meta')->addMultiple(static function () use ($type) {
                $type->getField('author_name')
                     ->setLabel('exampleBe.t.blogPost.field.authorName')
                     ->applyPreset()->input();
                
                $type->getField('date')
                     ->setLabel('exampleBe.t.blogPost.field.date')
                     ->applyPreset()->date();
            });
            
            $type->getField('body_text')
                 ->setLabel('exampleBe.t.blogPost.field.bodyText')
                 ->applyPreset()->textArea(['rte']);
        });
    }
    
}