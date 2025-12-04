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
use Runtime\BaseModel;
use Runtime\Serializer;
use Runtime\Entity\Factory;
use Runtime\Web\ApiResult;
use Runtime\Web\ModelFactory;
use Runtime\Web\RenderContainer;
use Runtime\Web\Messages\ClickMessage;
use Runtime\Web\Messages\Message;
use Runtime\Widget\Table\Table;
use Runtime\Widget\Table\TableMessage;
use Runtime\Widget\Table\TableStorageInterface;
use Runtime\Widget\ButtonModel;
use Runtime\Widget\Pagination;
use Runtime\Widget\RenderListModel;
use Runtime\Widget\RowButtonsModel;
use Runtime\Widget\WidgetResultModel;


class TableModel extends \Runtime\BaseModel
{
	var $component;
	var $widget_name;
	var $pagination_class_name;
	var $pagination_props;
	var $storage;
	var $top_buttons;
	var $limit;
	var $page;
	var $pages;
	var $row_selected;
	var $foreign_key;
	var $post_data;
	var $fields;
	var $items;
	var $styles;
	var $render_list;
	var $result;
	
	
	/**
	 * Create data storage
	 */
	function createDataStorage(){ return null; }
	
	
	/**
	 * Set data storage
	 */
	function setDataStorage($storage)
	{
		$this->storage = $storage;
	}
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("foreign_key")) $this->foreign_key = $params->get("foreign_key");
		if ($params->has("post_data")) $this->post_data = $params->get("post_data");
		if ($params->has("limit")) $this->limit = $params->get("limit");
		if ($params->has("page")) $this->page = $params->get("page");
		if ($params->has("styles")) $this->styles = $params->get("styles");
		/* Setup pagination */
		if ($params->has("pagination_class_name"))
		{
			$this->pagination_class_name = $params->get("pagination_class_name");
		}
		if ($params->has("pagination_props"))
		{
			$this->pagination_props = $params->get("pagination_props");
		}
		/* Setup fields */
		if ($params->has("fields"))
		{
			$this->fields = new \Runtime\Vector();
			$this->addFields($params->get("fields"));
		}
		/* Setup storage */
		if ($params->has("storage"))
		{
			$this->storage = $this->createModel($params->get("storage"));
		}
		if ($this->storage == null)
		{
			$this->storage = $this->createDataStorage($params);
		}
		/* Setup storage table */
		if ($this->storage != null)
		{
			$this->storage->setTable($this);
		}
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Render list */
		$this->render_list = $this->addWidget("Runtime.Widget.RenderListModel", new \Runtime\Map([
			"widget_name" => "render_list",
		]));
		/* Result */
		$this->result = $this->addWidget("Runtime.Widget.WidgetResultModel", new \Runtime\Map([
			"widget_name" => "result",
		]));
		/* Top buttons*/
		if ($params->has("top_buttons"))
		{
			$top_buttons = $params->get("top_buttons");
			$this->top_buttons = $this->addWidget("Runtime.Widget.RowButtonsModel", new \Runtime\Map([
				"widget_name" => "top_buttons",
				"styles" => new \Runtime\Vector("@top_buttons"),
				"buttons" => $top_buttons instanceof \Runtime\Collection ? $top_buttons : new \Runtime\Vector(),
			]));
		}
		/* Add layout */
		$this->layout->addComponent($this->pagination_class_name);
	}
	
	
	/**
	 * Add field
	 */
	function addField($field)
	{
		/* Create model */
		if ($field->has("model"))
		{
			$field->set("model", $this->createModel($field->get("model")));
		}
		/* Add field */
		$this->fields->append($field);
		/* Add component */
		if ($field->has("component"))
		{
			$this->layout->addComponent($field->get("component"));
		}
	}
	
	
	/**
	 * Add fields
	 */
	function addFields($fields)
	{
		for ($i = 0; $i < $fields->count(); $i++)
		{
			$this->addField($fields->get($i));
		}
	}
	
	
	/**
	 * Remove field
	 */
	function removeField($field_name)
	{
		$this->fields = $this->fields->filter(function ($field) use (&$field_name){ return $field->get("name") != $field_name; });
	}
	
	
	/**
	 * Returns item by row number
	 */
	function getItemByRowNumber($row_number){ return $this->items->get($row_number); }
	
	
	/**
	 * Returns item value
	 */
	function getItemValue($item, $field_name){ return $item->get($field_name); }
	
	
	/**
	 * Returns selected item
	 */
	function getSelectedItem(){ return $this->getItemByRowNumber($this->row_selected); }
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "limit", $data);
		$serializer->process($this, "page", $data);
		$serializer->process($this, "pages", $data);
		$serializer->process($this, "items", $data);
		parent::serialize($serializer, $data);
	}
	
	
	/**
	 * Set api result
	 */
	function setApiResult($res, $action)
	{
		if ($res == null) return;
		/* Load */
		if ($action == "search" || $action == "load" || $action == "reload")
		{
			if ($res->data->has("items")) $this->items = $res->data->get("items");
			if ($res->data->has("page")) $this->page = $res->data->get("page");
			if ($res->data->has("pages")) $this->pages = $res->data->get("pages");
			$this->result->setApiResult($res);
		}
	}
	
	
	/**
	 * Merge post data
	 */
	function mergePostData($post_data, $action)
	{
		if ($this->foreign_key)
		{
			$post_data->set("foreign_key", $this->foreign_key);
		}
		if ($this->post_data)
		{
			$post_data = $post_data->concat($this->post_data);
		}
		return $post_data;
	}
	
	
	/**
	 * Change page
	 */
	function changePage($page)
	{
		$this->page = $page;
		$this->refresh();
	}
	
	
	/**
	 * Load table data
	 */
	function loadData($container)
	{
		parent::loadData($container);
		$this->reload();
	}
	
	
	/**
	 * Reload table data
	 */
	function reload()
	{
		if (!$this->storage) return null;
		$res = $this->storage->load();
		$this->setApiResult($res, "reload");
		return $res;
	}
	
	
	/**
	 * Row click
	 */
	function onRowClick($row_number)
	{
		$this->emit(new \Runtime\Widget\Table\TableMessage(new \Runtime\Map([
			"name" => "row_click",
			"data" => new \Runtime\Map([
				"row_number" => $row_number,
			]),
		])));
	}
	
	
	/**
	 * Row button click
	 */
	function onRowButtonClick($message)
	{
		$this->emit(new \Runtime\Widget\Table\TableMessage(new \Runtime\Map([
			"name" => "row_button_click",
			"message" => $message,
		])));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Table.Table";
		$this->widget_name = "table";
		$this->pagination_class_name = "Runtime.Widget.Pagination";
		$this->pagination_props = new \Runtime\Map([
			"name" => "page",
		]);
		$this->storage = null;
		$this->top_buttons = null;
		$this->limit = 10;
		$this->page = 0;
		$this->pages = 0;
		$this->row_selected = -1;
		$this->foreign_key = null;
		$this->post_data = null;
		$this->fields = new \Runtime\Vector();
		$this->items = new \Runtime\Vector();
		$this->styles = new \Runtime\Vector();
		$this->render_list = null;
		$this->result = null;
	}
	static function getClassName(){ return "Runtime.Widget.Table.TableModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}