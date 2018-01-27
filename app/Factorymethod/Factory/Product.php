<?php
/**
 * User: Ly
 * Date: 2018/1/19
 * Time: 17:16
 */
namespace App\Factorymethod\Factory;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Factorymethod\Interfaces\FactoryInterface;
interface Product
{
	public function create(FactoryInterface $observer, Request $request);
	public function update(FactoryInterface $observer, Request $request);
	public function transform(Setting $options, Request $request);
}