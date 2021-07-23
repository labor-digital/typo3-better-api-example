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
 * Last modified: 2021.07.19 at 23:42
 */

declare(strict_types=1);

namespace LaborDigital\T3baExample\Configuration\ContentType;

use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureContentTypeInterface;
use LaborDigital\T3ba\Tool\Tca\ContentType\Builder\ContentType;

class Header implements ConfigureContentTypeInterface
{
    /**
     * @inheritDoc
     */
    public static function getCType(): string
    {
        return 'header';
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        // Content types are a way to extend existing CTypes with your own configuration.
        // The syntax works quite similar to TCA tables, but you only retrieve a single type
        // instead of the whole table object.
        
        // While you can do this configuration either directly in a TCA table class targeting the tt_content table,
        // doing it in a content type class, will nicely encapsulate the code, providing better readability.
        // Note: Content Elements registered through the ConfigureContentElementInterface, already provide
        // a configureContentType method which does the same as this method.
        
        // Because we remove the "header" palette globally in the content table
        // we need to re-add the "header" field for the "header" CType again. (header, header, header...)
        $type->getField('header')->applyPreset()->input(['required'])->moveTo();
    }
    
}