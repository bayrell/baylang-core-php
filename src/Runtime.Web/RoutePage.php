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
namespace Runtime\Web;

use Runtime\BaseModel;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Web\RouteInfo;
use Runtime\Web\RenderContainer;


class RoutePage extends \Runtime\Web\RouteInfo
{
	var $page;
	
	
	/**
	 * Process frontend data
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("page", new \Runtime\Serializer\StringType());
	}
	
	
	/**
	 * Render route
	 */
	function render($container)
	{
		$container->layout->current_page_model = "page_model";
		$container->layout->pages->set("page_model", $container->layout->createWidget("Runtime.BaseModel", new \Runtime\Map([
			"component" => $this->page,
		])));
		if ($this->data)
		{
			$title = $this->data->get("title");
			$is_full_title = $this->data->get("full_title", false);
			$container->layout->setPageTitle($title, $is_full_title);
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->page = "";
	}
	static function getClassName(){ return "Runtime.Web.RoutePage"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}