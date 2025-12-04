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

use Runtime\Entity\Hook;
use Runtime\Hooks\RuntimeHook;


class Components extends \Runtime\Hooks\RuntimeHook
{
	var $items;
	
	
	/**
	 * Hook factory
	 */
	static function hook($items)
	{
		return new \Runtime\Entity\Hook(static::getClassName(), new \Runtime\Map(["components" => $items]));
	}
	
	
	/**
	 * Hook factory
	 */
	static function header($items)
	{
		return new \Runtime\Entity\Hook(static::getClassName(), new \Runtime\Map(["header" => $items]));
	}
	
	
	/**
	 * Hook factory
	 */
	static function footer($items)
	{
		return new \Runtime\Entity\Hook(static::getClassName(), new \Runtime\Map(["footer" => $items]));
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("components")) $this->items->set("components", $params->get("components"));
		if ($params->has("footer")) $this->items->set("footer", $params->get("footer"));
		if ($params->has("header")) $this->items->set("header", $params->get("header"));
	}
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(\Runtime\Hooks\RuntimeHook::COMPONENTS, "components");
		$this->register(\Runtime\Hooks\RuntimeHook::LAYOUT_HEADER, "render_head");
		$this->register(\Runtime\Hooks\RuntimeHook::LAYOUT_FOOTER, "render_footer");
	}
	
	
	/**
	 * Components
	 */
	function components($params)
	{
		$params->get("components")->appendItems($this->items->get("components"));
	}
	
	
	/**
	 * Render head
	 */
	function render_head($params)
	{
		$params->get("components")->appendItems($this->items->get("header"));
	}
	
	
	/**
	 * Render footer
	 */
	function render_footer($params)
	{
		$params->get("components")->appendItems($this->items->get("footer"));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->items = new \Runtime\Map([
			"components" => new \Runtime\Vector(),
			"footer" => new \Runtime\Vector(),
			"header" => new \Runtime\Vector(),
		]);
	}
	static function getClassName(){ return "Runtime.Web.Hooks.Components"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}