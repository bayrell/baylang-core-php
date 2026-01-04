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
namespace Runtime\Widget\Table;

use Runtime\ApiResult;
use Runtime\BaseModel;
use Runtime\Serializer\IntegerType;
use Runtime\Serializer\ObjectType;
use Runtime\Web\RenderContainer;
use Runtime\Widget\Table\TableModel;


class TableLoader extends \Runtime\BaseModel
{
	var $table;
	var $foreign_key;
	var $api_name;
	var $method_name;
	var $page_name;
	var $page;
	var $limit;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("foreign_key", $rules->params ? $rules->params->get("foreign_key") : null);
		$rules->addType("page", new \Runtime\Serializer\IntegerType());
		$rules->addType("limit", new \Runtime\Serializer\IntegerType());
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params->has("table")) $this->table = $params->get("table");
		if ($params->has("foreign_key")) $this->foreign_key = $params->get("foreign_key");
		if ($params->has("api_name")) $this->api_name = $params->get("api_name");
		if ($params->has("method_name")) $this->method_name = $params->get("method_name");
		if ($params->has("page_name")) $this->page_name = $params->get("page_name");
		if ($params->has("page")) $this->page = $params->get("page");
		if ($params->has("limit")) $this->limit = $params->get("limit");
	}
	
	
	/**
	 * Load data
	 */
	function loadData($container)
	{
		$page = $container->request->query->get($this->page_name, 1);
		$this->page = $page - 1;
		return $this->reload();
	}
	
	
	/**
	 * Reload
	 */
	function reload()
	{
		$api_result = $this->layout->sendApi(new \Runtime\Map([
			"api_name" => $this->api_name,
			"method_name" => $this->method_name,
			"data" => new \Runtime\Map([
				"page" => $this->page,
				"limit" => $this->limit,
				"foreign_key" => $this->foreign_key,
			]),
		]));
		if ($api_result->isSuccess() && $api_result->data->has("items"))
		{
			$this->table->setItems($api_result->data->get("items"));
		}
		return $api_result;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->table = null;
		$this->foreign_key = null;
		$this->api_name = "";
		$this->method_name = "search";
		$this->page_name = "page";
		$this->page = 0;
		$this->limit = 10;
	}
	static function getClassName(){ return "Runtime.Widget.Table.TableLoader"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}