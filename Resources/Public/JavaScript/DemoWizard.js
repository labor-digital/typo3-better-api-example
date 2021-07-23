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
 * Last modified: 2021.07.16 at 18:15
 */

define(['TYPO3/CMS/Backend/FormEngineValidation'], function (engine) {
    return function (renderName) {
        var wizard = document.getElementById('demoWizard_' + renderName);
        if (!wizard) {
            return;
        }
        
        var target = document.querySelector('input[data-formengine-input-name="' + renderName + '"]');
        var button = wizard.querySelector('button');
        
        button.addEventListener('click', function (e) {
            e.preventDefault();
            target.value = Math.random();
            
            // This tells the TYPO3 Form engine to update the hidden field as well.
            engine.updateInputField(renderName);
        });
    };
});