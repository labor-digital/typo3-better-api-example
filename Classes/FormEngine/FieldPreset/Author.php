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


namespace LaborDigital\Typo3BetterApiExample\FormEngine\FieldPreset;


use LaborDigital\T3BA\Tool\Tca\Builder\FieldPreset\AbstractFieldPreset;
use LaborDigital\Typo3BetterApiExample\Configuration\Table\Article\AuthorTable as AuthorTable;

class Author extends AbstractFieldPreset
{
    
    /**
     * Defines the field to allow the selection for author records
     *
     * @param   array  $options  see {@link FieldPresetAutocompleteHelper::relationGroup()} for allowed options
     */
    public function applySelectAuthor(array $options = []): void
    {
        // We set the default max items value to 1 if not provided differently by the author
        $options['maxItems'] = $options['maxItems'] ?? 1;
        
        // A preset can also utilize other, existing presets to create convenience wrappers, like this one
        $this->field->applyPreset()->relationGroup(AuthorTable::class, $options);
    }
}
