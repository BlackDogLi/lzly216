<?php
/**
 * User: Ly
 * Date: 2018/1/8
 * Time: 11:09
 */
use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder {
	/**
	 *  自动填充数据
	 * @return void
	 */
	public function run() {
		\DB::table('admin_users')->delete();
		\DB::table('admin_users')->insert(array (
			0 => array(
				'id' => 1,
				'name' => 'Mr.li',
				'email' => '634647919@qq.com',
				'phone' => '18518757556',
				'password' => '123',
			)
		));
	}
}