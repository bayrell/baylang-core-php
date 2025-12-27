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

use Runtime\ApiResult;
use Runtime\Widget\ResultModel;
use Runtime\Widget\Dialog\DialogMessage;
use Runtime\Widget\Dialog\DialogModel;
use Runtime\Widget\Dialog\PromptDialog;


class PromptDialogModel extends \Runtime\Widget\Dialog\DialogModel
{
	var $component;
	var $action;
	var $title;
	var $title_button;
	var $title_button_styles;
	var $content;
	var $value;
	var $result;
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		$this->result = $this->createWidget("Runtime.Widget.ResultModel");
	}
	
	
	/**
	 * Set wait message
	 */
	function setWaitMessage()
	{
		$this->result->setWaitMessage();
	}
	
	
	/**
	 * Set api result
	 */
	function setApiResult($result)
	{
		$this->result->setApiResult($result);
	}
	
	
	/**
	 * Set value
	 */
	function setValue($value)
	{
		$this->value = $value;
	}
	
	
	/**
	 * Show
	 */
	function show()
	{
		parent::show();
		$this->result->clear();
	}
	
	
	/**
	 * Confirm
	 */
	function confirm()
	{
		$this->listener->emit(new \Runtime\Widget\Dialog\DialogMessage(new \Runtime\Map([
			"name" => "confirm",
			"action" => $this->action,
			"value" => $this->value,
		])));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Dialog.PromptDialog";
		$this->action = "";
		$this->title = "";
		$this->title_button = "";
		$this->title_button_styles = new \Runtime\Vector();
		$this->content = "";
		$this->value = "";
		$this->result = null;
	}
	static function getClassName(){ return "Runtime.Widget.Dialog.PromptDialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}