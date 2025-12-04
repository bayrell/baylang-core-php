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

use Runtime\BaseObject;
use Runtime\Web\LayoutModel;
use Runtime\Web\BasePageModel;
use Runtime\Web\RenderContainer;
use Runtime\Web\RenderResponse;
use Runtime\Web\RouteInfo;
use Runtime\Web\RouteList;


class BaseRoute extends \Runtime\BaseObject
{
	var $action;
	
	
	/**
	 * Constructor
	 */
	function __construct($params)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Returns layout name
	 */
	static function getLayoutName(){ return "default"; }
	
	
	/**
	 * Returns routes
	 */
	static function getRoutes()
	{
		return new \Runtime\Vector();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
	}
	static function getClassName(){ return "Runtime.Web.BaseRoute"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}