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
use Runtime\Widget\Button;
use Runtime\Widget\ButtonModel;
use Runtime\Widget\Dialog\ConfirmDialogModel;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Table\DeleteDialog;
use Runtime\Widget\Table\TableModel;


class DeleteDialogModel extends \Runtime\Widget\Dialog\ConfirmDialogModel
{
	var $widget_name;
	var $component;
	var $form;
	var $table;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("form")) $this->form = $params->get("form");
		if ($params->has("table")) $this->table = $params->get("table");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Change confirm button */
		$confirm_button = $this->buttons->getWidget("confirm_button");
		$confirm_button->content = "Delete";
		$confirm_button->styles = new \Runtime\Vector("danger");
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->widget_name = "delete_dialog";
		$this->component = "Runtime.Widget.Table.DeleteDialog";
	}
	static function getClassName(){ return "Runtime.Widget.Table.DeleteDialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}