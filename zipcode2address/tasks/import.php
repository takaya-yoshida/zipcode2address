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

namespace Fuel\Tasks;

class Import
{

	// インストール実行
	public function run($type = '')
	{
		if ( $type == '' || ! in_array($type, array('base', 'com')) )
		{
			echo "Error: specify the type.\n";
			return false;
		}

		\Module::load('zipsearch');

		// CSVファイルの存在確認
		$csv_file = APPPATH.'modules/zipcode2address/data/'.\Zipcode2address\Model_Zipcodedata::$csv_files[$type];
		if ( ! is_file($csv_file) || ! is_readable($csv_file) )
		{
			echo "Error: cvs file　was not found.\n";
			return false;
		}

		// DBチェックとテーブル作成
		try
		{
			\Zipcode2address\Model_Zipcodedata::check_install();
		}
		catch ( \Exception $e )
		{
			echo "Error: create table.\n";
			return false;
		}

		// インストール
		try
		{
			\DB::start_transaction();
			\Zipcode2address\Model_Zipcodedata::insert_zipdata($type);
			\DB::commit_transaction();
		}
		catch ( \Exception $e )
		{
			\DB::rollback_transaction();
			echo "Error: import data.\n";
			return false;
		}

		echo "OK! imported:".$type." data.";
		return true;
		
	}

}
