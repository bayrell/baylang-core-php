<?php
/*
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2025 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
*/
namespace Runtime\Widget;


class CSS extends \Runtime\Component
{
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ":root{--font-family: 'Arial', sans-serif;--font-size: 16px;--font-size-h1: 2.2rem;--font-size-h2: 1.8rem;--font-size-h3: 1.5rem;--font-size-h4: 1.2rem;--font-size-h5: 1rem;--font-size-h6: 1rem;--font-input-size: 16px;--line-height: 1.6;--space: 0.5rem;--color-background: #ffffff;--color-surface: #f8f9fa;--color-border: #e0e1e6;--color-shadow: rgba(0, 0, 0, 0.1);--color-primary: #337ab7;--color-primary-border: #337ab7;--color-primary-hover: #2563eb;--color-primary-text: #ffffff;--color-secondary: #6c757d;--color-secondary-border: #6c757d;--color-secondary-hover: #545b62;--color-secondary-text: #ffffff;--color-success: #198754;--color-success-border: #198754;--color-success-hover: #1e7e34;--color-success-text: #ffffff;--color-danger: #dc3545;--color-danger-danger: #dc3545;--color-danger-hover: #c82333;--color-danger-text: #ffffff;--color-warning: #fbbf24;--color-warning-border: #fbbf24;--color-warning-hover: #e0a800;--color-warinng-text: #212529;--color-info: #60a5fa;--color-info-border: #60a5fa;--color-info-hover: #117a8b;--color-info-text: #ffffff;--color-text: #212529;--color-text-secondary: #6c757d;--color-heading-text: #343a40;--color-link: var(--color-primary);--color-link-hover: var(--color-primary-dark);--color-hover: rgba(0, 0, 0, 0.05);--color-selected: var(--color-primary);--color-selected-text: #ffffff;--border-radius: 6px;--border-radius-large: 12px;--border-width: 1px;--shadow-small: 0 1px 2px 0 rgba(0, 0, 0, 0.05);--shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);--shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);--transition: 0.3s;--transition-type: ease-in-out;--transition-background: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type);--content-max-width: 1280px;--content-tablet-width: 1024px;--content-mobile-width: 768px;--padding-desktop: 2rem;--padding-tablet: 1.5rem;--padding-mobile: 1rem}.theme_dark{--color-background: #1a202c;--color-surface: #2d3748;--color-border: #4a5568;--color-shadow: rgba(0, 0, 0, 0.3);--color-primary: #63b3ed;--color-primary-light: #90cdf4;--color-primary-dark: #4299e1;--color-primary-text: #ffffff;--color-secondary: #a0aec0;--color-secondary-light: #1a202c;--color-secondary-dark: #cbd5e0;--color-secondary-text: #718096;--color-success: #68d391;--color-success-light: #5cb870;--color-success-dark: #1e7e34;--color-success-text: #ffffff;--color-danger: #fc8181;--color-danger-light: #e4606d;--color-danger-dark: #c82333;--color-danger-text: #ffffff;--color-warning: #f6e05e;--color-warning-light: #ffcd38;--color-warning-dark: #e0a800;--color-warinng-text: #212529;--color-info: #63b3ed;--color-info-light: #4bd2e3;--color-info-dark: #117a8b;--color-info-text: #ffffff;--color-text: #e2e8f0;--color-text-secondary: #a0aec0;--color-heading-text: #ffffff;--color-link: var(--color-primary);--color-link-hover: red}body, html{scroll-behavior: smooth;transition: background-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.root_container{font-family: var(--font-family);font-size: var(--font-size);line-height: var(--line-height);box-sizing: border-box;width: 100%;padding: 0;margin: 0}.root_container *{box-sizing: border-box}.root_container h1, .root_container h2, .root_container h3, .root_container h4, .root_container h5, .root_container h6{font-family: var(--font-family);color: var(--color-heading-text);margin-top: calc(var(--space) * 3);margin-bottom: var(--space);line-height: 1.2;transition: background-color var(--transition) ease,\n\t\tcolor var(--transition) ease}.root_container h1{font-size: var(--font-size-h1);margin-top: 0}.root_container h2{font-size: var(--font-size-h2)}.root_container h3{font-size: var(--font-size-h3)}.root_container h4{font-size: var(--font-size-h4)}.root_container h5{font-size: var(--font-size-h5)}.root_container h6{font-size: var(--font-size-h6)}.root_container p{margin-bottom: 1em;transition: background-color var(--transition) ease,\n\t\tcolor var(--transition) ease}.link{text-decoration: none;color: var(--color-link);cursor: pointer}.link:hover, .link:visited:hover{text-decoration: underline;color: var(--color-link-hover)}.link:visited{text-decoration: none;color: var(--color-link)}.nolink{text-decoration: inherit;color: inherit}.nolink:hover, .nolink:visited, .nolink:visited:hover{text-decoration: inherit;color: inherit}.cursor{cursor: pointer}.nowrap{white-space: nowrap}.bold{font-weight: bold}.nobold{font-weight: normal}.underline{text-decoration: underline}.center{text-align: center}.left{text-align: left}.right{text-align: right}.clear{clear: both}.hidden{display: none}.inline-block{display: inline-block}.scroll-lock{overflow: hidden}.scroll-lock .core_ui_root{padding-right: 15px}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.CSS"; }
}