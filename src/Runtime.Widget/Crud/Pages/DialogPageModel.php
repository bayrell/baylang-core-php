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
use Runtime\Entity\Factory;
use Runtime\Web\ModelFactory;
use Runtime\Web\RenderContainer;
use Runtime\Widget\Crud\Pages\DialogPage;
use Runtime\Widget\Crud\CrudFactory;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Table\TableDialogModel;
use Runtime\Widget\RowButtonsModel;


class DialogPageModel extends \Runtime\BaseModel
{
	var $component;
	var $crud;
	var $add_form;
	var $edit_form;
	var $delete_form;
	var $table;
	var $top_buttons;
	
	
	/**
	 * Returns api name
	 */
	function getApiName($action){ return ""; }
	
	
	/**
	 * Returns primary key
	 */
	function getPrimaryKey(){ return new \Runtime\Vector(); }
	
	
	/**
	 * Returns item fields
	 */
	function getItemFields(){ return new \Runtime\Vector(); }
	
	
	/**
	 * Returns form fields
	 */
	function getFormFields($action){ return new \Runtime\Vector(); }
	
	
	/**
	 * Returns table fields
	 */
	function getTableFields($action){ return new \Runtime\Vector(); }
	
	
	/**
	 * Table title
	 */
	function getTableTitle($params){ return ""; }
	
	
	/**
	 * Returns routes
	 */
	function getParam($name, $params = null)
	{
		/* Crud widget settings */
		if ($name == "form.add.model.name") return "Runtime.Widget.Form.FormModel";
		if ($name == "form.edit.model.name") return "Runtime.Widget.Form.FormModel";
		if ($name == "form.delete.model.name") return "Runtime.Widget.Form.FormModel";
		if ($name == "table.model.name") return "Runtime.Widget.Table.TableDialogModel";
		if ($name == "table.page.name") return "p";
		/* Form storage settings */
		if ($name == "form.add.storage" || $name == "form.edit.storage" || $name == "form.delete.storage")
		{
			$storage_name = "Runtime.Widget.Form.FormSaveStorage";
			if ($name == "form.delete.storage")
			{
				$storage_name = "Runtime.Widget.Form.FormDeleteStorage";
			}
			return new \Runtime\Entity\Factory($storage_name, new \Runtime\Map([
				"api_name" => $this->getApiName("save"),
			]));
		}
		/* Table storage settings */
		if ($name == "table.storage")
		{
			return new \Runtime\Entity\Factory("Runtime.Widget.Table.TableStorage", new \Runtime\Map([
				"api_name" => $this->getApiName("search"),
			]));
		}
		return "";
	}
	
	
	/**
	 * Init widget settings
	 */
	function initParams($params)
	{
		parent::initParams($params);
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Add form */
		$this->add_form = $this->addWidget($this->getParam("form.add.model.name"), new \Runtime\Map([
			"widget_name" => "form",
			"primary_key" => $this->getPrimaryKey(),
			"storage" => $this->getParam("form.add.storage", new \Runtime\Map(["widget" => $this])),
			"fields" => $this->getFormFields("add_form"),
		]));
		/* Edit form */
		$this->edit_form = $this->addWidget($this->getParam("form.edit.model.name"), new \Runtime\Map([
			"widget_name" => "form",
			"primary_key" => $this->getPrimaryKey(),
			"storage" => $this->getParam("form.edit.storage", new \Runtime\Map(["widget" => $this])),
			"fields" => $this->getFormFields("edit_form"),
		]));
		/* Delete form */
		$this->delete_form = $this->addWidget($this->getParam("form.delete.model.name"), new \Runtime\Map([
			"widget_name" => "delete_form",
			"primary_key" => $this->getPrimaryKey(),
			"storage" => $this->getParam("form.delete.storage", new \Runtime\Map(["widget" => $this])),
		]));
		/* Add table */
		$this->table = $this->addWidget($this->getParam("table.model.name"), new \Runtime\Map([
			"widget_name" => "table",
			"styles" => new \Runtime\Vector("border"),
			"get_title" => function ($params)
			{
				return $this->getTableTitle($params);
			},
			"storage" => $this->getParam("table.storage", new \Runtime\Map(["widget" => $this])),
			"page" => $this->layout->request_query->get($this->getParam("table.page.name"), 1) - 1,
			"pagination_props" => new \Runtime\Map([
				"name" => $this->getParam("table.page.name"),
			]),
			"add_form" => $this->add_form,
			"edit_form" => $this->edit_form,
			"delete_form" => $this->delete_form,
			"fields" => $this->getTableFields("table"),
		]));
		/* Add top buttons */
		$this->top_buttons = $this->addWidget("Runtime.Widget.RowButtonsModel", new \Runtime\Map([
			"widget_name" => "top_buttons",
			"styles" => new \Runtime\Vector("top_buttons"),
			"buttons" => new \Runtime\Vector(
				new \Runtime\Web\ModelFactory("Runtime.Widget.Table.AddButtonModel", new \Runtime\Map([
					"table" => $this->table,
				])),
			),
		]));
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
	function buildTitle($container){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Crud.Pages.DialogPage";
		$this->crud = null;
		$this->add_form = null;
		$this->edit_form = null;
		$this->delete_form = null;
		$this->table = null;
		$this->top_buttons = null;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Pages.DialogPageModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}