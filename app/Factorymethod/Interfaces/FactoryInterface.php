<?php
/**
 * User: Ly
 * Date: 2018/1/19
 * Time: 16:42
 */
namespace App\Factorymethod\Interfaces;

interface FactoryInterface
{
	public function creatorFail ($error);
	public function creatorSuccess ($model);
}