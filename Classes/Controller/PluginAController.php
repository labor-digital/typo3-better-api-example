<?php
/*
 * Copyright 2020 LABOR.digital
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
 * Last modified: 2020.09.03 at 20:42
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Controller;


use LaborDigital\T3BA\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3BA\ExtConfig\ExtConfigContext;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3BA\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3BA\Tool\BackendPreview\BackendListLabelRendererInterface;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3BA\Tool\DataHook\DataHookContext;
use Neunerlei\TinyTimy\DateTimy;

class PluginAController extends BetterContentActionController
    implements ConfigurePluginInterface, BackendPreviewRendererInterface, BackendListLabelRendererInterface
{

    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('exampleBe.p.pluginA.title')
                     ->setDescription('exampleBe.p.pluginA.desc')
                     ->setActions(['index', 'detail'])
                     ->registerSaveHook(static::class)
                     ->registerFormHook(static::class);
    }

    /**
     * This method is executed every time the data handler saves a record of this content element to the database
     *
     * @param   \LaborDigital\T3BA\Tool\DataHook\DataHookContext  $context
     */
    public function saveHook(DataHookContext $context): void
    {
        $data           = $context->getData();
        $data['header'] = 'The current time is: ' . (new DateTimy())->formatDateAndTime();
        $context->setData($data);
    }

    /**
     * This method is called every time the backend renders the form for this content element.
     * It allows you to hide, add or modify the record as well as the form fields on the fly.
     *
     * @param   \LaborDigital\T3BA\Tool\DataHook\DataHookContext  $context
     */
    public function formHook(DataHookContext $context): void
    {
        $data           = $context->getData();
        $data['header'] = 'Here goes the timestamp when you saved the record...';
        $context->setData($data);
    }

    /**
     * This method is used to render the backend preview of this content element.
     * You can either return a string to show, or set the body and header on the context object
     *
     * @param   \LaborDigital\T3BA\Tool\BackendPreview\BackendPreviewRendererContext  $context
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        $context->setBody(
            'My Ext Base Plugin, can render it\'s own backend preview with ease!' .
            '<br>Last Modified: ' . (new DateTimy($context->getRow()['tstamp']))->formatDateAndTime());
    }

    /**
     * This method allows you to render a string representation of this content element.
     * The string will be rendered to the redaction team in the list view
     *
     * @param   array  $row
     * @param   array  $options
     *
     * @return string
     */
    public function renderBackendListLabel(array $row, array $options): string
    {
        return 'My list label: ' . $row['header'];
    }

    public function indexAction()
    {
        return 'INDEX';
    }

    public function detailAction()
    {
        return 'DETAIL';
    }
}
