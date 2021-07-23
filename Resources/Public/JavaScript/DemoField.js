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
 * Last modified: 2021.07.16 at 17:09
 */

define([], function () {
    return function (renderName) {
        var field = document.getElementById('demoField_' + renderName);
        if (!field) {
            return;
        }
        
        var onButton = field.querySelector('.demoField__button--on');
        var offButton = field.querySelector('.demoField__button--off');
        var input = field.querySelector('input');
        var activeClass = 'btn-info';
        
        // noinspection EqualityComparisonWithCoercionJS
        if (input.value != '1') {
            offButton.classList.add(activeClass);
        } else {
            onButton.classList.add(activeClass);
        }
        
        onButton.addEventListener('click', function (e) {
            e.preventDefault();
            input.value = '1';
            onButton.classList.add(activeClass);
            offButton.classList.remove(activeClass);
        });
        
        offButton.addEventListener('click', function (e) {
            e.preventDefault();
            input.value = '0';
            offButton.classList.add(activeClass);
            onButton.classList.remove(activeClass);
        });
    };
});