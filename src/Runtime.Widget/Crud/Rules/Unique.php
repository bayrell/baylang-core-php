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
namespace Runtime\Widget\Crud\Rules;

use Runtime\re;
use Runtime\BaseStruct;
use Runtime\Exceptions\RuntimeException;
use Runtime\ORM\Connection;
use Runtime\ORM\Cursor;
use Runtime\ORM\Provider;
use Runtime\ORM\Query;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\Relation;
use Runtime\Widget\Crud\CrudService;
use Runtime\Widget\Crud\Rules\CrudRule;


class Unique extends \Runtime\Widget\Crud\Rules\CrudRule
{
	var $keys;
	
	
	/**
	 * Before save item
	 */
	function onSaveBefore($service)
	{
		$connection = $service->getConnection();
		$provider = \Runtime\rtl::getContext()->provider("Runtime.ORM.Provider");
		$table_name = $service->getTableName();
		$q = (new \Runtime\ORM\Query())->select()->from($table_name)->addRawField("count(1) as c");
		/* Add filter */
		if ($this->keys)
		{
			for ($i = 0; $i < $this->keys->count(); $i++)
			{
				$field_name = $this->keys->get($i);
				$value = $service->data->get($field_name);
				$q->where($field_name, "=", $value);
			}
		}
		/* Add primary key */
		if ($service->item != null && !$service->item->isNew())
		{
			$filter = $provider->getPrimaryFilter($service->getTableName(), $service->item->toMap(), true);
			for ($i = 0; $i < $filter->count(); $i++)
			{
				$item = $filter[$i];
				$item->op = "!=";
				$q->addFilter($item);
			}
		}
		/* Execute query */
		$res = $connection->fetchVar($q, "c");
		if ($res > 0)
		{
			for ($i = 0; $i < $this->keys->count(); $i++)
			{
				$name = $this->keys->get($i);
				$service->rules->addFieldError($name, "Field must be unique");
			}
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->keys = null;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Rules.Unique"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}