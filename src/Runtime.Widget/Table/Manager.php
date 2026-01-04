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
use Runtime\Message;
use Runtime\Method;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Web\RenderContainer;
use Runtime\Widget\Dialog\DialogModel;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Table\TableLoader;
use Runtime\Widget\Table\TableModel;


class Manager extends \Runtime\BaseModel
{
	var $form;
	var $table;
	var $api_name;
	var $page_name;
	var $foreign_key;
	
	/* Dialog */
	var $dialog_action;
	var $dialog;
	var $loader;
	
	/* Functions */
	var $title;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("foreign_key", $rules->params ? $rules->params->get("foreign_key") : null);
		$rules->addType("form", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"autocreate" => true,
			"extends" => "Runtime.Widget.Form.FormModel",
			"pk_type" => $rules->params ? $rules->params->get("pk_type") : null,
			"item_type" => $rules->params ? $rules->params->get("item_type") : null,
		])));
		$rules->addType("table", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"autocreate" => true,
			"extends" => "Runtime.Widget.Table.TableModel",
			"item_type" => $rules->params ? $rules->params->get("item_type") : null,
		])));
		$rules->addType("dialog", new \Runtime\Serializer\ObjectType(new \Runtime\Map(["extends" => "Runtime.Widget.Dialog.DialogModel"])));
		$rules->addType("loader", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"extends" => "Runtime.Widget.Table.TableLoader",
			"foreign_key" => $rules->params ? $rules->params->get("foreign_key") : null,
		])));
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params->has("api_name")) $this->api_name = $params->get("api_name");
		if ($params->has("foreign_key")) $this->foreign_key = $params->get("foreign_key");
		if ($params->has("page_name")) $this->page_name = $params->get("page_name");
		if ($params->has("title")) $this->title = $params->get("title");
	}
	
	
	/**
	 * Init widget
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		if ($params->has("dialog")) $this->dialog = $params->get("dialog");
		else $this->dialog = $this->createWidget("Runtime.Widget.Dialog.DialogModel");
		$this->dialog->addEventListener("hide", new \Runtime\Method($this, "onDialogHide"));
		if ($params->has("form")) $this->form = $params->get("form");
		else $this->form = $this->createWidget("Runtime.Widget.Form.FormModel");
		if ($params->has("table")) $this->table = $params->get("table");
		else $this->table = $this->createWidget("Runtime.Widget.Table.TableModel");
		if ($params->has("loader")) $this->loader = $params->get("loader");
		else $this->loader = $this->createWidget("Runtime.Widget.Table.TableLoader", new \Runtime\Map([
			"api_name" => $this->api_name,
			"foreign_key" => $this->foreign_key,
			"page_name" => $this->page_name,
			"table" => $this->table,
		]));
	}
	
	
	/**
	 * Load data
	 */
	function loadData($container)
	{
		$this->loader->loadData($container);
	}
	
	
	/**
	 * Set foreign key
	 */
	function setForeignKey($key)
	{
		$this->foreign_key = $key;
		$this->loader->foreign_key = $key;
	}
	
	
	/**
	 * Returns dialog action
	 */
	function getDialogAction()
	{
		if ($this->dialog_action == "save")
		{
			if ($this->form->pk) return "edit";
			else return "add";
		}
		return $this->dialog_action;
	}
	
	
	/**
	 * Returns dialog title
	 */
	function getDialogTitle($action)
	{
		if ($this->title) return \Runtime\rtl::apply($this->title, new \Runtime\Vector($action, $this->form->item));
		return "";
	}
	
	
	/**
	 * Show add dialog
	 */
	function showAddDialog()
	{
		$this->dialog_action = "save";
		$this->form->clear();
		$this->dialog->show();
	}
	
	
	/**
	 * Show edit dialog
	 */
	function showEditDialog($item)
	{
		$this->dialog_action = "save";
		$this->form->clear();
		$this->form->setPrimaryKey($item);
		$this->form->setItem($item);
		$this->dialog->show();
	}
	
	
	/**
	 * Show delete dialog
	 */
	function showDeleteDialog($item)
	{
		$this->dialog_action = "delete";
		$this->form->clear();
		$this->form->setPrimaryKey($item);
		$this->form->setItem($item);
		$this->dialog->show();
	}
	
	
	/**
	 * On hide dialog
	 */
	function onDialogHide()
	{
		$this->dialog_action = "";
	}
	
	
	/**
	 * On save
	 */
	function onSave()
	{
		$this->form->setWaitMessage();
		$result = $this->layout->sendApi(new \Runtime\Map([
			"api_name" => $this->api_name,
			"method_name" => "save",
			"data" => new \Runtime\Map([
				"pk" => $this->form->pk,
				"item" => $this->form->item,
				"foreign_key" => $this->foreign_key,
			]),
		]));
		$this->form->setApiResult($result);
		if ($result->isSuccess())
		{
			$this->loader->reload();
			$this->dialog->hide();
		}
	}
	
	
	/**
	 * On delete
	 */
	function onDelete()
	{
		$this->form->setWaitMessage();
		$result = $this->layout->sendApi(new \Runtime\Map([
			"api_name" => $this->api_name,
			"method_name" => "delete",
			"data" => new \Runtime\Map([
				"pk" => $this->form->pk,
				"foreign_key" => $this->foreign_key,
			]),
		]));
		$this->form->setApiResult($result);
		if ($result->isSuccess())
		{
			$this->loader->reload();
			$this->dialog->hide();
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->form = null;
		$this->table = null;
		$this->api_name = "";
		$this->page_name = "page";
		$this->foreign_key = null;
		$this->dialog_action = "";
		$this->dialog = null;
		$this->loader = null;
		$this->title = null;
	}
	static function getClassName(){ return "Runtime.Widget.Table.Manager"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}