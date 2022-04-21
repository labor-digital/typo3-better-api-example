<?php
/*
 * Copyright 2022 LABOR.digital
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
 * Last modified: 2022.04.13 at 11:43
 */

declare(strict_types=1);

namespace LaborDigital\T3baExample;

use LaborDigital\T3ba\Core\Di\ContainerAwareTrait;
use Neunerlei\PathUtil\Path;
use TYPO3\CMS\Impexp\Import;

class ext_update
{
    use ContainerAwareTrait;
    
    public function main()
    {
        $importFileName = Path::join(__DIR__, 'Initialisation/data.xml');
        $import = $this->makeInstance(Import::class);
        $import->init();
        $import->update = false;
        $import->global_ignore_pid = true;
        $import->force_all_UIDS = true;
        $import->enableLogging = true;
        $import->loadFile($importFileName);
        $import->importData($this->cs()->typoContext->site()->getCurrent()->getRootPageId());
    }
    
    public function access()
    {
        return true;
    }
}