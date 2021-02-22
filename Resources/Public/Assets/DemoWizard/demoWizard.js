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
 * Last modified: 2021.02.22 at 16:02
 */

(
    function () {
        var wizards = document.querySelectorAll('.demoWizard');
        for (var i = 0; i < wizards.length; i++) {
            (
                function () {
                    var wizard = wizards[i];
                    var targetName = wizard.dataset.target ?? null;

                    if (typeof targetName !== 'string') {
                        return;
                    }

                    // If there is a better way of setting a value than selecting and updating both fields
                    // at the same time, please let me know!
                    var target = document.querySelector('input[data-formengine-input-name="' + targetName + '"]');
                    var hidden = document.querySelector('input[name="' + targetName + '"]');
                    var button = wizard.querySelector('button');

                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        var rand = Math.random();
                        target.value = rand;
                        hidden.value = rand;
                    });
                }
            )();
        }
    }
)();
