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

use Runtime\Web\ApiResult;
use Runtime\Widget\Button;
use Runtime\Widget\ButtonModel;
use Runtime\Widget\Table\RefreshButton;
use Runtime\Widget\Table\TableModel;
use Runtime\Widget\WidgetResultModel;


class AddButtonModel extends \Runtime\Widget\ButtonModel
{
	var $widget_name;
	var $content;
	var $styles;
	var $table;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("table")) $this->table = $params->get("table");
	}
	
	
	/**
	 * Refresh
	 */
	function onClick($data = null)
	{
		$this->table->showAdd();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->widget_name = "add_button";
		$this->content = "Add";
		$this->styles = new \Runtime\Vector("success");
		$this->table = null;
	}
	static function getClassName(){ return "Runtime.Widget.Table.AddButtonModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}