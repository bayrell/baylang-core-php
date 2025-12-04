<?php
/*!
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
namespace Runtime\Web\Hooks;

use Runtime\re;
use Runtime\ModelProxy;
use Runtime\RawString;
use Runtime\Exceptions\RuntimeException;
use Runtime\Web\CoreUI;
use Runtime\Web\RenderContainer;
use Runtime\Web\RenderResponse;
use Runtime\Web\Response;
use Runtime\Web\Hooks\AppHook;


class ResponseHook extends \Runtime\Web\Hooks\AppHook
{
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::RESPONSE, 9999);
	}
	
	
	/**
	 * Response
	 */
	function response($params)
	{
		$container = $params->get("container");
		$response = $container->response;
		if (!($response instanceof \Runtime\Web\RenderResponse)) return;
		if ($response->content != null) return;
		$class_name = $container->layout->getCoreUI();
		if ($class_name == null)
		{
			throw new \Runtime\Exceptions\RuntimeException("Class name is null");
		}
		/* Create component */
		$component = \Runtime\rtl::newInstance($class_name);
		$component->container = $container;
		$component->layout = $container->layout;
		$component->model = $container->layout;
		/* Render component */
		$content = "<!doctype html>\n";
		$content .= \Runtime\RawString::normalize($component->render());
		/* Set result */
		$response->content = $content;
		return $params;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Web.Hooks.ResponseHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}