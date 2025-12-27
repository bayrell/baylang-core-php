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

namespace Runtime\Core\Commands;

use Runtime\ModuleDescription;
use Runtime\Vector;
use Runtime\Console\BaseCommand;

class Install extends BaseCommand
{
	/**
	 * Returns name
	 */
	static function getName(){ return "core:install"; }
	
	
	/**
	 * Returns description
	 */
	static function getDescription(){ return "Install baylang"; }
	
	
	/**
	 * Run task
	 */
	function run()
	{
		$this->copyAssets();
		$this->downloadVue();
	}
	
	
	/**
	 * Returns assets path
	 */
	function getAssetsPath()
	{
		$module = new \ReflectionClass(ModuleDescription::class);
		return realpath(dirname($module->getFileName()) . "/../../assets");
	}
	
	
	/**
	 * Returns dest path
	 */
	function getDestPath()
	{
		$context = \Runtime\rtl::getContext();
		return realpath(\Runtime\fs::join(new Vector($context->base_path, "public/assets")));
	}
	
	
	/**
	 * Copy assets
	 */
	function copyAssets()
	{
		$source_path = $this->getAssetsPath();
		$dest_path = $this->getDestPath();
		
		if (!$source_path || !$dest_path)
		{
			\Runtime\rtl::error("assets folder not found");
			return;
		}
		
		\Runtime\rtl::print("Copy runtime");
		copy($source_path . "/runtime.js", $dest_path . "/runtime.js");
		copy($source_path . "/runtime.min.js", $dest_path . "/runtime.min.js");
	}
	
	
	/**
	 * Download file
	 */
	function download($file, $dest)
	{
		$content = file_get_contents($file);
		file_put_contents($dest, $content);
	}
	
	
	/**
	 * Download Vue
	 */
	function downloadVue()
	{
		$source_path = $this->getAssetsPath();
		$dest_path = $this->getDestPath();
		
		if (!$source_path || !$dest_path)
		{
			\Runtime\rtl::error("assets folder not found");
			return;
		}
		
		\Runtime\rtl::print("Download vue");
		$this->download("https://unpkg.com/vue@3/dist/vue.global.js",
			$dest_path . "/vue.global.js"
		);
		$this->download("https://unpkg.com/vue@3/dist/vue.global.prod.js",
			$dest_path . "/vue.global.prod.js"
		);
	}
}