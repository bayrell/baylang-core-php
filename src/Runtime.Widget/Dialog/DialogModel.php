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
namespace Runtime\Widget\Dialog;

use Runtime\Widget\RowButtonsModel;
use Runtime\Widget\WidgetResultModel;
use Runtime\Widget\Dialog\BaseDialogModel;
use Runtime\Widget\Dialog\Dialog;


class DialogModel extends \Runtime\Widget\Dialog\BaseDialogModel
{
	var $action;
	var $title;
	var $content;
	var $width;
	var $component;
	var $data;
	var $styles;
	var $buttons;
	var $result;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("content")) $this->content = $params->get("content");
		if ($params->has("styles")) $this->styles = $params->get("styles");
		if ($params->has("title")) $this->title = $params->get("title");
		if ($params->has("width")) $this->width = $params->get("width");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Add button */
		$this->buttons = $this->addWidget("Runtime.Widget.RowButtonsModel", new \Runtime\Map([
			"widget_name" => "buttons",
			"styles" => new \Runtime\Vector("@widget_dialog__buttons", "align-end"),
		]));
		/* Add result */
		$this->result = $this->addWidget("Runtime.Widget.WidgetResultModel", new \Runtime\Map([
			"styles" => new \Runtime\Vector("margin_top"),
		]));
	}
	
	
	/**
	 * Show dialog
	 */
	function show()
	{
		$this->result->clear();
		parent::show();
	}
	
	
	/**
	 * Set title
	 */
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	
	/**
	 * Set width
	 */
	function setWidth($value)
	{
		$this->width = $value;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
		$this->title = "";
		$this->content = "";
		$this->width = "";
		$this->component = "Runtime.Widget.Dialog.Dialog";
		$this->data = new \Runtime\Map();
		$this->styles = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Widget.Dialog.DialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}