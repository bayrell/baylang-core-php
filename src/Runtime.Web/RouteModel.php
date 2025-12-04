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

use Runtime\Serializer;
use Runtime\Web\RouteInfo;
use Runtime\Web\RenderContainer;


class RouteModel extends \Runtime\Web\RouteInfo
{
	var $model;
	var $model_params;
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "model", $data);
		$serializer->process($this, "model_params", $data);
		parent::serialize($serializer, $data);
	}
	
	
	/**
	 * Render route
	 */
	function render($container)
	{
		/* Check page model */
		if ($this->model == "") return;
		if (!\Runtime\rtl::classExists($this->model)) return;
		/* Render page model */
		$container->renderPageModel($this->model, $this->model_params);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->model = null;
		$this->model_params = null;
	}
	static function getClassName(){ return "Runtime.Web.RouteModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}