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

use Runtime\BaseObject;
use Runtime\Callback;
use Runtime\Serializer;
use Runtime\Web\ApiResult;
use Runtime\Web\BaseModel;
use Runtime\Web\ModelFactory;
use Runtime\Web\Messages\ClickMessage;
use Runtime\Widget\Button;
use Runtime\Widget\ButtonModel;
use Runtime\Widget\Dialog\ConfirmDialogModel;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Table\SaveDialog;
use Runtime\Widget\Table\TableModel;


class SaveDialogModel extends \Runtime\Widget\Dialog\ConfirmDialogModel
{
	var $action;
	var $widget_name;
	var $component;
	var $add_form;
	var $edit_form;
	var $table;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("action")) $this->action = $params->get("action");
		if ($params->has("table")) $this->table = $params->get("table");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		if ($params->has("add_form"))
		{
			if ($params->get("add_form") instanceof \Runtime\Web\ModelFactory)
			{
				$this->add_form = $params->get("add_form")->factory($this);
			}
			else
			{
				$this->add_form = $params->get("add_form");
			}
		}
		if ($params->has("edit_form"))
		{
			if ($params->get("edit_form") instanceof \Runtime\Web\ModelFactory)
			{
				$this->edit_form = $params->get("edit_form")->factory($this);
			}
			else
			{
				$this->edit_form = $params->get("edit_form");
			}
		}
	}
	
	
	/**
	 * Add close button click
	 */
	function onCloseButtonClick($message)
	{
		parent::onCloseButtonClick();
		if ($this->action == "add" && $this->add_form) $this->add_form->setItem(null);
		if ($this->action == "edit" && $this->edit_form) $this->edit_form->setItem(null);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
		$this->widget_name = "save_dialog";
		$this->component = "Runtime.Widget.Table.SaveDialog";
	}
	static function getClassName(){ return "Runtime.Widget.Table.SaveDialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}