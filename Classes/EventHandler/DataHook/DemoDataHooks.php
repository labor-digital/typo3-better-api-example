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
 * Last modified: 2021.02.02 at 16:01
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\EventHandler\DataHook;


use LaborDigital\T3BA\Core\Di\PublicServiceInterface;
use LaborDigital\T3BA\Tool\DataHook\DataHookContext;
use LaborDigital\T3BA\Tool\Rendering\FlashMessageRenderingService;

/**
 * Class DemoDataHooks
 *
 * DataHooks for tables should always be extracted into their own files.
 * This has multiple reasons. 1. it make the code much more organized and 2.
 * all classes below Configuration/ are by default not part of the composer autoload schema
 * and also not part of the typical auto-wiring of the container. Everything inside "Classes" is however.
 * So, while you COULD register a PSR-4 namespace AND configure the container to recognize your table
 * classes in the Configuration directory, I would not recommend that.
 *
 * This class will show flash messages every time an action was executed.
 * No actual data is changed here. If you want a closer look on how dataHooks work take a look at the
 * plugin examples: {@link \LaborDigital\Typo3BetterApiExample\Controller\PluginAController::saveHook()}
 *
 * @package LaborDigital\Typo3BetterApiExample\EventHandler\DataHook
 */
class DemoDataHooks implements PublicServiceInterface
{
    /**
     * @var \LaborDigital\T3BA\Tool\Rendering\FlashMessageRenderingService
     */
    protected $flashMessages;

    /**
     * DemoDataHooks constructor.
     *
     * @param   \LaborDigital\T3BA\Tool\Rendering\FlashMessageRenderingService  $flashMessages
     */
    public function __construct(FlashMessageRenderingService $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }

    public function tableSaveHook(DataHookContext $c): void
    {
        $this->flashMessages->addOk(
            'Executed save hook on a per-table level: ' . $c->getTableName(),
            static::class . '::' . __FUNCTION__);
    }

    public function typeSaveHook(DataHookContext $c)
    {
        $this->flashMessages->addOk(
            'Executed save hook on a per-type level: ' . $c->getTableName() . ' type: ' . $c->getRow()['type'],
            static::class . '::' . __FUNCTION__);
    }

    public function fieldSaveHook(DataHookContext $c): void
    {
        $this->flashMessages->addOk(
            'Executed save hook on a field level: Field: ' . $c->getKey() . ' ' .
            $c->getTableName() . ' type: ' . $c->getRow()['type'],
            static::class . '::' . __FUNCTION__);
    }

    public function tableOverrideSaveHook(DataHookContext $c): void
    {
        $this->flashMessages->addOk(
            'Executed save hook on a per-table level: ' . $c->getTableName()
            . ', that was registered in the override file!',
            static::class . '::' . __FUNCTION__);
    }

    public function fieldOverrideSaveHook(DataHookContext $c): void
    {
        $this->flashMessages->addOk(
            'Executed save hook on a field level, that was registered in the override file: Field: '
            . $c->getKey() . ' ' . $c->getTableName() . ' type: ' . $c->getRow()['type'],
            static::class . '::' . __FUNCTION__);
    }

    public function typeFieldSaveHook(DataHookContext $c)
    {
        $this->flashMessages->addOk(
            'Executed save hook on a per-type and field level: Field: ' . $c->getKey() . ' ' .
            $c->getTableName() . ' type: ' . $c->getRow()['type'],
            static::class . '::' . __FUNCTION__);
    }

    public function flexFormFieldSaveHook(DataHookContext $c)
    {
        $this->flashMessages->addOk(
            'Executed save hook on a flex form field: Field: ' . $c->getKey() . ' ' .
            $c->getTableName(),
            static::class . '::' . __FUNCTION__);
    }

    public function flexFormFieldInSectionFormHook(DataHookContext $c)
    {
        $this->flashMessages->addInfo(
            'Executed form hook on a flex form field inside a section: Field: ' . $c->getKey() . ' ' .
            $c->getTableName() . '. As you see, this hook is called once, for every section you create.',
            static::class . '::' . __FUNCTION__);
    }
}
