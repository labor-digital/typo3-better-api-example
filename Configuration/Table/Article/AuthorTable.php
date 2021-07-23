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


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;

class AuthorTable implements ConfigureTcaTableInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->setTitle('exampleBe.t.author.title');
        
        $table->enablePreviewLink('authorDetail');
        
        // To make the table easier to read in the backend
        // we use the "last_name" column as main label column
        // in addition to that we add the "first_name" column as secondary label column and force
        // the backend to render every time (using true as second parameter)
        $table->setLabelColumn('last_name')
              ->setLabelAlternativeColumns('first_name', true);
        
        // This table should always be rendered before articles in the backend
        $table->setListPosition(ArticleTable::class);
        
        $type = $table->getType();
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getPalette('name')
                 ->setLabel('exampleBe.t.author.palette.name')
                 ->addMultiple(static function () use ($type) {
                     $type->getField('first_name')->applyPreset()->input(['required']);
                     $type->getField('last_name')->applyPreset()->input(['required']);
                 });
            
            $type->getField('slug')
                 ->applyPreset()->slug(['last_name', 'first_name'], ['separator' => '-']);
            
            $type->getField('birthday')->applyPreset()->date();
            
            // In order for the backend editors to see which articles are written by the author
            // we create a reflection of the relation. TYPO3 calls this the "opposite" of an mm relation.
            // The relation we want to reflect here, is done in the ArticleTable class through the "author" field.
            // As you can see there, the "author" field, defines the "mmOpposite" option, that points to this field here.
            // On this end of the relation, we now point back to the article table and the "author" field.
            // This allows both involved tables to create new relations.
            
            // TIP: The "readOnly" option, can be removed if you want to actively link articles to an
            // author from the author table.
            $type->getField('articles')
                 ->applyPreset()
                 ->relationGroupOpposite(ArticleTable::class, 'author', ['readOnly']);
        });
    }
    
}
