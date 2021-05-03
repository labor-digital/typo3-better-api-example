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
 * Last modified: 2021.05.01 at 15:24
 */

declare(strict_types=1);


namespace LaborDigital\T3baExample\EventHandler\DataHook;


use LaborDigital\T3ba\Tool\FormEngine\Custom\Field\CustomFieldDataHookContext;

class ButtonDataHook
{
    public function formHook(CustomFieldDataHookContext $context)
    {
        dbge($context);
    }
}