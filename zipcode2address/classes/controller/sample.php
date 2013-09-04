<?php
/**
 * Part of the Address Search Modules.
 *
 * @version    0.1
 * @author     Takaya Yoshida
 * @license    MIT License
 * @copyright  2013 Qript.inc
 * @link       http://www.qript.co.jp/
 */

namespace Zipcode2address;

class Controller_Sample extends \Controller
{

	public function action_index()
	{
		// サンプルは開発環境でのみ閲覧許可
		if ( \Fuel::$env != \Fuel::DEVELOPMENT )
		{
			return false;
		}
		return \View::forge('sample');
	}

}
