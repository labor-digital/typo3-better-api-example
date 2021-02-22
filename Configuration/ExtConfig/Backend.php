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
 * Last modified: 2021.02.19 at 14:24
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig;


use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\Backend\BackendConfigurator;
use LaborDigital\T3BA\ExtConfigHandler\Backend\ConfigureBackendInterface;

class Backend implements ConfigureBackendInterface
{
    /**
     * @inheritDoc
     */
    public static function configureBackend(BackendConfigurator $configurator, ExtConfigContext $context): void
    {
        // It is trivial to register a new css file to be loaded in the backend using this configuration option.
        // Just point it to the file you want to load and it will handle the rest for you.
        // You can either provide an absolute url, a relative url (starting with "/") or
        // a path relative to your extension.

        // Pro-tip: All config options allow you to work with placeholders for your {{extKey}}, {{vendor}} and
        // {{extKeyWithVendor}}. This way, you can keep your options relative to the extension without hard-coding the
        // values, or relying on $context->getExtKey() and friends
        $configurator->registerCss('EXT:{{extKey}}/Resources/Public/Assets/backend.css');

        // This works for javascript as well
        $configurator->registerJs('EXT:{{extKey}}/Resources/Public/Assets/backend.js');

        // You can also configure the RTE directly in your PHP code, without messing with yml files.
        // By default you start configuring the "default" preset, but can always switch to a different one
        // using the "preset" option. In a simple use-case like this you can start at the "editor.config" node.
        // The script will take care of including the processing.yaml, Base.yaml and Plugins.yaml for you.
        // For advanced configuration scenarios you can also put in a config containing "editor" => ["config" => ...]
        $configurator->registerRteConfig([
            'format_tags'      => 'p',
            'autoParagraph'    => false,
            'fillEmptyBlocks'  => false,
            'removePlugins'    => 'magicline,pagebreak,image',
            'extraPlugins'     => 'indentblock',
            'contentsCss'      => [
                'EXT:rte_ckeditor/Resources/Public/Css/contents.css',
            ],
            'removeFormatTags' => 'b,big,cite,code,del,dfn,em,font,i,ins,kbd,q,s,samp,small,span,strike,strong,sub,sup,tt,u,var,div',
            'toolbar'          => [
                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                ['Undo', 'Redo',],
                ['Link', 'Unlink',],
                ['Outdent', 'Indent'],
                [
                    'Bold',
                    'Italic',
                    'BulletedList',
                    'NumberedList',
                    'Subscript',
                    'Superscript',
                    'RemoveFormat',
                ],
                ['SpecialChar',],
                ['Source', 'Maximize',],
            ],
        ]);
    }

}
