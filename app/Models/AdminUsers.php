<?php
/**
 * User: Ly
 * Date: 2018/1/9
 * Time: 12:14
 */
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUsers extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

}