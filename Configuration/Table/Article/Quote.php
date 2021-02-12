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
 * Last modified: 2021.02.12 at 17:13
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\Table\Article;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3BA\Tool\Tca\Builder\Type\Table\TcaTable;

class Quote implements ConfigureTcaTableInterface
{

    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        // We want to use this table as a "inline-only" table, therefore we mark it as "hidden"
        $table->setHidden();

        // Otherwise there is not much special in this table...
        $table->setTitle('exampleBe.t.quote.title');
        $table->setLabelColumn('quote');

        $type = $table->getType();
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('quote')
                 ->setLabel('exampleBe.t.quote.field.quote')
                 ->applyPreset()->textArea(['required', 'maxLength' => 500]);

            $type->getField('author')
                 ->setLabel('exampleBe.t.quote.field.author')
                 ->applyPreset()->selectAuthor(['maxItems' => 1, 'required']);
        });
    }

}
