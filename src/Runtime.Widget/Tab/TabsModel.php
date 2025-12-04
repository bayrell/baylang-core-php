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
namespace Runtime\Widget\Tab;

use Runtime\lib;
use Runtime\BaseModel;
use Runtime\Widget\Tab\Tabs;


class TabsModel extends \Runtime\BaseModel
{
	var $active;
	var $render;
	var $component;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("active")) $this->active = $params->get("active");
		if ($params->has("render")) $this->render = $params->get("render");
	}
	
	
	/**
	 * Returns true if active
	 */
	function isActive($name){ return $this->active == $name; }
	
	
	/**
	 * Set active
	 */
	function setActive($active)
	{
		$this->active = $active;
	}
	
	
	/**
	 * Can show
	 */
	function canShow($tab_key)
	{
		$tab = $this->items->findItem(\Runtime\lib::equalAttr("key", $tab_key));
		if ($tab == null) return false;
		if ($tab->has("href") && $tab->get("key") != $tab_key) return false;
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->active = "";
		$this->render = true;
		$this->component = "Runtime.Widget.Tab.Tabs";
	}
	static function getClassName(){ return "Runtime.Widget.Tab.TabsModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}