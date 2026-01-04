"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Unit == 'undefined') Runtime.Unit = {};
Runtime.Unit.AssertHelper = class
{
	/**
	 * Check equals of types
	 */
	static equalValueType(value1, value2)
	{
		let message = "Type mismatch \"" + String(value1) + String("\" and \"") + String(value2) + String("\"");
		let type1 = Runtime.rtl.getType(value1);
		let type2 = Runtime.rtl.getType(value2);
		Runtime.rtl.assert(type1 == type2, message);
	}
	
	
	/**
	 * Check equals of types
	 */
	static equalType(value1, type1)
	{
		let type2 = Runtime.rtl.getType(value1);
		let message = "Type mismatch. Needs \"" + String(type1) + String("\". Exists \"") + String(type2) + String("\"");
		Runtime.rtl.assert(type1 == type2, message);
	}
	
	
	/**
	 * Check class name
	 */
	static equalClass(value, class_name)
	{
		let message = "Class \"" + String(class_name) + String("\" not found");
		let is_object = value instanceof Runtime.BaseObject || value instanceof Runtime.Map || value instanceof Runtime.Vector;
		Runtime.rtl.assert(is_object, message);
		Runtime.rtl.assert(value.constructor.getClassName() == class_name, message);
	}
	
	
	/**
	 * Check equals of values
	 */
	static equalValue(value1, value2, message)
	{
		if (message == undefined) message = "";
		this.equalValueType(value1, value2);
		if (value1 instanceof Runtime.Vector)
		{
			this.equalVector(value1, value2);
			return;
		}
		if (value1 instanceof Runtime.Map)
		{
			this.equalMap(value1, value2);
			return;
		}
		if (message == "") message = "\"" + String(value1) + String("\" != \"") + String(value2) + String("\"");
		Runtime.rtl.assert(value1 === value2, message);
	}
	
	
	/**
	 * Check equals of two vectors
	 */
	static equalVector(c1, c2)
	{
		if (c1.count() != c2.count())
		{
			let message = "Vectors has different counts";
			Runtime.rtl.assert(false, message);
		}
		for (let i = 0; i < c1.count(); i++)
		{
			let value1 = c1.get(i);
			let value2 = c2.get(i);
			this.equalValue(value1, value2);
		}
	}
	
	
	/**
	 * Check equals of two maps
	 */
	static equalMap(d1, d2)
	{
		let d1_keys = Runtime.rtl.list(d1.keys());
		let d2_keys = Runtime.rtl.list(d2.keys());
		for (let i = 0; i < d1_keys.count(); i++)
		{
			let key1 = d1_keys.get(i);
			if (!d2.has(key1))
			{
				let message = "Map does not has key \"" + String(key1) + String("\"");
				Runtime.rtl.assert(false, message);
			}
			let value1 = d1.get(key1);
			let value2 = d2.get(key1);
			this.equalValue(value1, value2);
		}
		for (let i = 0; i < d2_keys.count(); i++)
		{
			let key2 = d2_keys.get(i);
			if (!d1.has(key2))
			{
				let message = "Map does not has key \"" + String(key2) + String("\"");
				Runtime.rtl.assert(false, message);
			}
		}
	}
	
	
	/* ========= Class init functions ========= */
	_init()
	{
	}
	static getClassName(){ return "Runtime.Unit.AssertHelper"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Unit.AssertHelper"] = Runtime.Unit.AssertHelper;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Unit == 'undefined') Runtime.Unit = {};
Runtime.Unit.Test = class extends Runtime.Entity.Entity
{
	/* ========= Class init functions ========= */
	_init()
	{
		super._init();
	}
	static getClassName(){ return "Runtime.Unit.Test"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Unit.Test"] = Runtime.Unit.Test;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Unit == 'undefined') Runtime.Unit = {};
Runtime.Unit.TestProvider = class extends Runtime.BaseProvider
{
	/**
	 * Start provider
	 */
	async start()
	{
		this.tests_list = Runtime.rtl.getContext().getEntities("Runtime.Unit.UnitTest");
	}
	
	
	/**
	 * Returns commands list
	 */
	getTests(){ return this.tests_list; }
	
	
	/**
	 * Returns unit test by pos
	 */
	get(pos){ return this.tests_list.get(pos); }
	
	
	/**
	 * Returns count of unit tests
	 */
	count(){ return this.tests_list.count(); }
	
	
	/**
	 * Run
	 */
	static async run(test_name)
	{
		if (test_name == undefined) test_name = "";
		let provider = new Runtime.Unit.TestProvider();
		await provider.start();
		if (test_name == "")
		{
			Runtime.rtl.print("List of all tests:");
			for (let i = 0; i < provider.count(); i++)
			{
				let test = provider.get(i);
				Runtime.rtl.print(i + 1 + String(") ") + String(test.name));
			}
			return;
		}
		await provider.runTestByName(test_name);
	}
	
	
	/**
	 * Run
	 */
	static async runAll()
	{
		let provider = new Runtime.Unit.TestProvider();
		await provider.start();
		for (let i = 0; i < provider.count(); i++)
		{
			let test = provider.get(i);
			Runtime.rtl.print("Run " + String(test.name));
			let error_code = await provider.runTestByName(test.name);
			if (error_code != 1)
			{
				return;
			}
		}
	}
	
	
	/**
	 * Run test
	 */
	async runTestByName(test_name)
	{
		let error_code = 0;
		let arr = Runtime.rs.split("::", test_name);
		if (arr.count() == 1)
		{
			/* Run all test in class */
			error_code = await this.runTestClass(arr.get(0));
		}
		else
		{
			/* Run specific test */
			error_code = await this.runTestMethod(arr.get(0), arr.get(1));
		}
		return error_code;
	}
	
	
	/**
	 * Returns true if TestMethod
	 */
	static isTestMethod(annotations)
	{
		for (let j = 0; j < annotations.count(); j++)
		{
			let annotation = annotations.get(j);
			if (annotation instanceof Runtime.Unit.Test)
			{
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * Returns all test methods
	 */
	getTestMethods(class_name)
	{
		let getMethodsList = new Runtime.Method(class_name, "getMethodsList");
		let getMethodInfoByName = new Runtime.Method(class_name, "getMethodInfoByName");
		let methods = getMethodsList.apply();
		methods = methods.filter((method_name) =>
		{
			let method_info = getMethodInfoByName.apply(Runtime.Vector.create([method_name]));
			return this.constructor.isTestMethod(method_info);
		});
		return methods;
	}
	
	
	/**
	 * Run all test in class
	 */
	async runTestClass(class_name)
	{
		let error_code = 1;
		let methods = this.getTestMethods(class_name);
		for (let i = 0; i < methods.count(); i++)
		{
			let method_name = methods.get(i);
			let result = await this.runTestMethod(class_name, method_name);
			if (result != 1)
			{
				error_code = -1;
				break;
			}
		}
		if (error_code == 1)
		{
			Runtime.rtl.print(Runtime.rtl.color("green", "Success"));
		}
		return error_code;
	}
	
	
	/**
	 * Run test method
	 */
	async runTestMethod(class_name, method_name)
	{
		let error_code = 0;
		try
		{
			let callback = new Runtime.Method(class_name, method_name);
			if (!callback.exists())
			{
				let obj = Runtime.rtl.newInstance(class_name);
				callback = new Runtime.Method(obj, method_name);
			}
			if (callback.exists())
			{
				let res = callback.apply();
				if (res instanceof Promise) await res;
				error_code = 1;
				Runtime.rtl.print(class_name + String("::") + String(method_name) + String(" ") + String(Runtime.rtl.color("green", "Ok")));
			}
			else
			{
				throw new Runtime.Exceptions.ItemNotFound(class_name + String("::") + String(method_name), "Method");
			}
		}
		catch (_ex)
		{
			if (_ex instanceof Runtime.Exceptions.AssertException)
			{
				var e = _ex;
				Runtime.rtl.print(class_name + String("::") + String(method_name) + String(" ") + String(Runtime.rtl.color("red", "Error: " + String(e.getErrorMessage()))));
				error_code = e.getErrorCode();
			}
			else
			{
				throw _ex;
			}
		}
		return error_code;
	}
	
	
	/* ========= Class init functions ========= */
	_init()
	{
		super._init();
		this.tests_list = Runtime.Vector.create([]);
	}
	static getClassName(){ return "Runtime.Unit.TestProvider"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Unit.TestProvider"] = Runtime.Unit.TestProvider;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Unit == 'undefined') Runtime.Unit = {};
Runtime.Unit.UnitTest = class extends Runtime.Entity.Entity
{
	constructor(api_name)
	{
		super(Runtime.Map.create({
			"name": api_name,
		}));
	}
	
	
	/* ========= Class init functions ========= */
	_init()
	{
		super._init();
	}
	static getClassName(){ return "Runtime.Unit.UnitTest"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Unit.UnitTest"] = Runtime.Unit.UnitTest;
