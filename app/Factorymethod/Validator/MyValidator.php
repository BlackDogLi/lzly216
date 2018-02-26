<?php
/**
 * 自定义验证
 * User: Ly
 * Date: 2018/2/5
 * Time: 15:14
 */
namespace App\Factorymethod\Validator;

class MyValidator
{
	public function validateFlag($attribute, $value, $parameters, $validator)
	{
		return preg_match("/^[a-zA-Z0-9_-]+$/", $value);
	}
}