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
namespace Runtime\Widget\Form;

use Runtime\lib;
use Runtime\BaseObject;
use Runtime\BaseModel;
use Runtime\Serializer;
use Runtime\Entity\Factory;
use Runtime\Web\ApiResult;
use Runtime\Web\ModelFactory;
use Runtime\Web\RenderContainer;
use Runtime\Widget\Form\Form;
use Runtime\Widget\Form\FormMessage;
use Runtime\Widget\Form\FormStorageInterface;
use Runtime\Widget\RowButtonsModel;
use Runtime\Widget\WidgetResultModel;


class FormModel extends \Runtime\BaseModel
{
	var $component;
	var $widget_name;
	var $form_title;
	var $form_content;
	var $styles;
	
	/* Fields */
	var $foreign_key;
	var $post_data;
	var $fields;
	var $fields_error;
	var $field_settings;
	
	/* Data */
	var $row_number;
	var $pk;
	var $item;
	var $primary_key;
	var $storage;
	var $bottom_buttons;
	
	/* Form result */
	var $load;
	var $result;
	var $show_result;
	
	
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
		if ($params->has("field_settings")) $this->field_settings = $params->get("field_settings");
		if ($params->has("foreign_key")) $this->foreign_key = $params->get("foreign_key");
		if ($params->has("form_content")) $this->form_content = $params->get("form_content");
		if ($params->has("form_title")) $this->form_title = $params->get("form_title");
		if ($params->has("post_data")) $this->post_data = $params->get("post_data");
		if ($params->has("show_result")) $this->show_result = $params->get("show_result");
		if ($params->has("styles")) $this->styles = $params->get("styles");
		/* Setup params */
		if ($params->has("fields"))
		{
			$this->fields = new \Runtime\Vector();
			$this->addFields($params->get("fields"));
		}
		/* Setup storage */
		if ($params->has("storage"))
		{
			$storage = $params->get("storage");
			if ($storage instanceof \Runtime\Entity\Factory) $this->storage = $storage->factory();
			else if (\Runtime\rtl::isString($storage)) $this->storage = \Runtime\rtl::newInstance($storage, new \Runtime\Vector($this));
			else $this->storage = $storage;
		}
		if ($this->storage == null)
		{
			$this->storage = $this->createDataStorage($params);
		}
		/* Setup storage form */
		if ($this->storage != null)
		{
			$this->storage->setForm($this);
		}
		/* Setup primary key */
		if ($params->has("pk")) $this->pk = $params->get("pk");
		if ($params->has("primary_key")) $this->primary_key = $params->get("primary_key");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Load result */
		$this->load = $this->addWidget("Runtime.Widget.WidgetResultModel", new \Runtime\Map([
			"widget_name" => "load",
		]));
		/* Result */
		$this->result = $this->addWidget("Runtime.Widget.WidgetResultModel", new \Runtime\Map([
			"widget_name" => "result",
			"styles" => new \Runtime\Vector("margin_top"),
		]));
		/* Buttons */
		$this->bottom_buttons = $this->addWidget("Runtime.Widget.RowButtonsModel", new \Runtime\Map([
			"widget_name" => "bottom_buttons",
			"styles" => new \Runtime\Vector("bottom_buttons", "center"),
		]));
	}
	
	
	/**
	 * Add field
	 */
	function addField($field)
	{
		/* Create model */
		if ($field->has("model"))
		{
			$model = $field->get("model");
			if ($model instanceof \Runtime\Web\ModelFactory)
			{
				$instance = $model->factory($this);
				$field->set("model", $instance);
			}
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
	 * Get field
	 */
	function getField($field_name){ return $this->fields->findItem(\Runtime\lib::equalAttr("name", $field_name)); }
	
	
	
	/**
	 * Remove field
	 */
	function removeField($field_name)
	{
		$this->fields = $this->fields->filter(function ($field) use (&$field_name){ return $field->get("name") != $field_name; });
	}
	
	
	/**
	 * Returns field result
	 */
	function getFieldResult($field_name)
	{
		if ($this->fields_error->has($field_name)) return $this->fields_error->get($field_name);
		return new \Runtime\Vector();
	}
	
	
	/**
	 * Clear form
	 */
	function clear()
	{
		$this->pk = null;
		$this->row_number = -1;
		$this->clearError();
		$this->clearItem();
	}
	
	
	/**
	 * Clear form error
	 */
	function clearError()
	{
		$this->fields_error = new \Runtime\Map();
		$this->result->clear();
	}
	
	
	/**
	 * Clear form
	 */
	function clearItem()
	{
		$this->item = new \Runtime\Map();
		for ($i = 0; $i < $this->fields->count(); $i++)
		{
			$field = $this->fields->get($i);
			$field_name = $field->get("name");
			$default_value = $field->get("default", "");
			$this->item->set($field_name, \Runtime\Serializer::copy($default_value));
		}
	}
	
	
	/**
	 * Field changed event
	 */
	function onFieldChange($field_name, $value)
	{
		$old_value = $this->item->get($field_name);
		$this->item->set($field_name, $value);
		$this->emitAsync(new \Runtime\Widget\Form\FormMessage(new \Runtime\Map([
			"name" => "field_change",
			"field_name" => $field_name,
			"old_value" => $old_value,
			"value" => $value,
		])));
	}
	
	
	/**
	 * Returns item value
	 */
	function getItemValue($field_name){ return $this->item->get($field_name); }
	
	
	/**
	 * Returns primary key
	 */
	function getPrimaryKey($item){ return $item->intersect($this->primary_key); }
	
	
	/**
	 * Set item
	 */
	function setItem($item)
	{
		if ($item == null)
		{
			$this->pk = null;
			$this->item = new \Runtime\Map();
		}
		else
		{
			$this->pk = $this->getPrimaryKey($item);
			$this->item = $item;
		}
	}
	
	
	/**
	 * Set row number
	 */
	function setRowNumber($row_number)
	{
		$this->row_number = $row_number;
	}
	
	
	/**
	 * Process frontend data
	 */
	function serialize($serializer, $data)
	{
		$serializer->process($this, "pk", $data);
		$serializer->process($this, "item", $data);
		$serializer->process($this, "load", $data);
		parent::serialize($serializer, $data);
	}
	
	
	/**
	 * Set api result
	 */
	function setApiResult($res, $action)
	{
		if ($res == null) return;
		/* Set data */
		if ($res->data->has("item") && $res->data->has("item") != null)
		{
			$this->item = $res->data->get("item");
		}
		if ($res->data->has("pk") && $res->data->get("pk") != null)
		{
			$this->pk = $res->data->get("pk");
		}
		if ($res->data->has("fields"))
		{
			$this->fields_error = $res->data->get("fields");
		}
		/* Load */
		if ($action == "load")
		{
			$this->load->setApiResult($res);
		}
		/* Submit */
		if ($action == "submit")
		{
			$this->result->setApiResult($res);
		}
	}
	
	
	/**
	 * Returns post item
	 */
	function getPostItem(){ return \Runtime\Serializer::copy($this->item); }
	
	
	/**
	 * Merge post data
	 */
	function mergePostData($post_data, $action)
	{
		if ($this->foreign_key)
		{
			$post_data->set("foreign_key", $this->foreign_key);
			if ($post_data->has("item"))
			{
				$item = $post_data->get("item");
				$keys = $this->foreign_key->keys();
				for ($i = 0; $i < $keys->count(); $i++)
				{
					$key = $keys->get($i);
					$item->set($key, $this->foreign_key->get($key));
				}
			}
		}
		if ($this->post_data)
		{
			$post_data = $post_data->concat($this->post_data);
		}
		return $post_data;
	}
	
	
	/**
	 * Load form data
	 */
	function loadData($container)
	{
		parent::loadData($container);
		$this->loadForm();
	}
	
	
	/**
	 * Load form
	 */
	function loadForm()
	{
		if (!$this->pk) return;
		if (!$this->storage) return;
		/* Load data */
		$res = $this->storage->load();
		$this->setApiResult($res, "load");
	}
	
	
	/**
	 * Submit form
	 */
	function submit()
	{
		if (!$this->storage) return null;
		$this->result->setWaitMessage();
		$res = $this->storage->submit();
		$this->setApiResult($res, "submit");
		$this->emitAsync(new \Runtime\Widget\Form\FormMessage(new \Runtime\Map([
			"name" => "submit",
			"result" => $res,
		])));
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Form.Form";
		$this->widget_name = "form";
		$this->form_title = "";
		$this->form_content = "";
		$this->styles = new \Runtime\Vector();
		$this->foreign_key = null;
		$this->post_data = null;
		$this->fields = new \Runtime\Vector();
		$this->fields_error = new \Runtime\Map();
		$this->field_settings = new \Runtime\Map();
		$this->row_number = -1;
		$this->pk = null;
		$this->item = new \Runtime\Map();
		$this->primary_key = new \Runtime\Vector();
		$this->storage = null;
		$this->load = null;
		$this->result = null;
		$this->show_result = true;
	}
	static function getClassName(){ return "Runtime.Widget.Form.FormModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}