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
namespace Runtime\Widget\Crud\Pages;

use Runtime\BaseModel;
use Runtime\Serializer;
use Runtime\Web\RenderContainer;
use Runtime\Widget\Crud\CrudFactory;


class IndexPageModel extends \Runtime\BaseModel
{
	var $crud;
	
	
	/**
	 * Init widget settings
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("crud"))
		{
			$this->crud = $params->get("crud");
			if ($this->crud instanceof \Runtime\Map)
			{
				$serializer = new \Runtime\Serializer();
				$serializer->setFlag(\Runtime\Serializer::ALLOW_OBJECTS);
				$this->crud = $serializer->decodeObject($this->crud);
			}
		}
	}
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		if ($serializer->isEncode())
		{
			$serializer->process($this, "crud", $data);
		}
		parent::serialize($serializer, $data);
	}
	
	
	/**
	 * Build title
	 */
	function buildTitle($container)
	{
		$this->crud->buildTitle($container);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->crud = null;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Pages.IndexPageModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}