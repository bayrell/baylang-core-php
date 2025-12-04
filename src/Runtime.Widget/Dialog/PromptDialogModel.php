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

use Runtime\Callback;
use Runtime\Web\Messages\ClickMessage;
use Runtime\Widget\ButtonModel;
use Runtime\Widget\Dialog\DialogMessage;
use Runtime\Widget\Dialog\DialogModel;
use Runtime\Widget\Dialog\DialogModelException;
use Runtime\Widget\Dialog\PromptDialog;


class PromptDialogModel extends \Runtime\Widget\Dialog\DialogModel
{
	var $component;
	var $value;
	var $old_value;
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Setup close buttons */
		$this->buttons->addButton(new \Runtime\Map([
			"content" => "Cancel",
			"widget_name" => "cancel_button",
			"styles" => new \Runtime\Vector(
				"gray",
			),
			"events" => new \Runtime\Map([
				"click" => new \Runtime\Callback($this, "onCloseButtonClick"),
			]),
		]));
		/* Setup confirm button */
		$this->buttons->addButton(new \Runtime\Map([
			"content" => "Ok",
			"widget_name" => "confirm_button",
			"styles" => new \Runtime\Vector(
				"success",
			),
			"events" => new \Runtime\Map([
				"click" => new \Runtime\Callback($this, "onConfirmButtonClick"),
			]),
		]));
		/* Buttons style */
		$this->buttons->styles->add("align-end");
	}
	
	
	/**
	 * Set value
	 */
	function setValue($value)
	{
		$this->value = $value;
	}
	
	
	/**
	 * Add close button click
	 */
	function onCloseButtonClick($message)
	{
		$this->hide();
	}
	
	
	/**
	 * Add confirm button click
	 */
	function onConfirmButtonClick($message)
	{
		try
		{
			$this->emit(new \Runtime\Widget\Dialog\DialogMessage(new \Runtime\Map([
				"name" => "confirm",
				"value" => $this->value,
			])));
		}
		catch (\Runtime\Widget\Dialog\DialogModelException $e)
		{
			$this->result->setException($e);
			return;
		}
		$this->hide();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Dialog.PromptDialog";
		$this->value = "";
		$this->old_value = "";
	}
	static function getClassName(){ return "Runtime.Widget.Dialog.PromptDialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}