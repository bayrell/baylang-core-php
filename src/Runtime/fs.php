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
namespace Runtime;

use Runtime\re;


class fs
{
	const DIRECTORY_SEPARATOR = "/";
	
	
	/**
	 * Join
	 */
	static function join($arr)
	{
		$arr = $arr->filter(function ($s){ return $s != ""; });
		$path = \Runtime\rs::join(static::DIRECTORY_SEPARATOR, $arr);
		$path = \Runtime\re::replace("\\/+", "/", $path);
		$path = \Runtime\re::replace("\\/\\.\\/", "/", $path);
		$path = \Runtime\re::replace("\\/+\$", "", $path);
		return $path;
	}
	
	
	/**
	 * Return true if path is exists
	 * @param string path
	 * @param boolean
	 */
	static function exists($filepath)
	{
		return file_exists($filepath);
	}
	
	
	/**
	 * Return true if path is folder
	 * @param string path
	 * @param boolean
	 */
	static function isDir($filepath){ return static::isFolder($filepath); }
	
	
	/**
	 * Return true if path is folder
	 * @param string path
	 * @param boolean
	 */
	static function isFolder($filepath)
	{
		return file_exists($filepath) && is_dir($filepath);
	}
	
	
	/**
	 * Return true if path is file
	 * @param string path
	 * @param boolean
	 */
	static function isFile($filepath)
	{
		return file_exists($filepath) && is_file($filepath);
	}
	
	
	/**
	 * Read local file
	 */
	static function readFile($filepath, $ch = "utf8")
	{
		$filepath = realpath($filepath);
		if ($filepath == false) return "";
		if (!file_exists($filepath)) return "";
		return file_get_contents($filepath);
		return "";
	}
	
	
	/**
	 * Save local file
	 */
	static function saveFile($filepath, $content = "", $ch = "utf8")
	{
		if ($filepath == false) return "";
		return @file_put_contents($filepath, $content);
		return "";
	}
	
	
	/**
	 * Rename file
	 */
	static function rename($file_path, $file_new_path)
	{
		rename($file_path, $file_new_path);
	}
	
	
	/**
	 * Remove file
	 */
	static function unlink($file_path)
	{
		unlink($file_path);
	}
	
	
	/**
	 * Scan directory
	 */
	static function listDir($dirpath)
	{
		$arr = scandir($dirpath);
		$arr = array_filter($arr, function($s){ return $s != "." && $s != ".."; });
		return \Runtime\Vector::create(array_values($arr));
		return null;
	}
	
	
	/**
	 * Scan directory recursive
	 */
	static function listDirRecursive($dirpath, $parent_name = "")
	{
		$res = new \Runtime\Vector();
		$items = static::listDir($dirpath);
		for ($i = 0; $i < $items->count(); $i++)
		{
			$item_name = $items->get($i);
			$item_path = static::join(new \Runtime\Vector($dirpath, $item_name));
			$item_name2 = static::join(new \Runtime\Vector($parent_name, $item_name));
			if ($item_name == "." || $item_name == "..") continue;
			$item_name2 = \Runtime\rs::removeFirstSlash($item_name2);
			$res->push($item_name2);
			$is_dir = static::isDir($item_path);
			if ($is_dir)
			{
				$sub_items = static::listDirRecursive($item_path, $item_name2);
				$res->appendItems($sub_items);
			}
		}
		return $res;
	}
	
	
	/**
	 * Make dir recursive
	 */
	static function mkdir($filepath, $mode = "755")
	{
		if ($filepath == false) return false;
		return @mkdir($filepath, octdec($mode), true);
		return "";
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.fs"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}