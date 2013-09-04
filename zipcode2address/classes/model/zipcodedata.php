<?php
/**
 * Part of the Zipcode to Address Modules.
 *
 * @version    0.1
 * @author     Takaya Yoshida
 * @license    MIT License
 * @copyright  2013 Qript.inc
 * @link       http://www.qript.co.jp/
 */

namespace Zipcode2address;

class Model_Zipcodedata extends \Model
{
	
	// テーブル名
	protected static $_table_name = 'zipcode_data';

	// CSVファイル名
	public static $csv_files = array(
		'base' => 'KEN_ALL.CSV',
		'com'  => 'JIGYOSYO.CSV',
	);


	// テーブル名
	public static function table_name()
	{
		return self::$_table_name;
	}


	// 郵便番号から検索
	public static function search($zipcode = '')
	{
		$result = \DB::select('pref_kana', 'city_kana', 'town_kana', 'company_kana', 'pref', 'city', 'town', 'company')
			->from(self::$_table_name)
			->where('zipcode', '=', $zipcode)
			->as_assoc()
			->execute();
		if ( ! $result || ! isset($result[0]) ) return false;
		return $result[0];
	}


	// イントール準備
	public static function check_install()
	{

		// テーブル確認or作成
		$table_name = self::$_table_name;
		if ( strlen($table_name) && ! \DBUtil::table_exists($table_name) )
		{
			\DBUtil::create_table(
				$table_name,
				array(
					'zipcode'       => array('constraint' => 7,   'type' => 'varchar'),
					'pref_kana'     => array('constraint' => 10,  'type' => 'varchar'),
					'city_kana'     => array('constraint' => 100, 'type' => 'varchar'),
					'town_kana'     => array('constraint' => 100, 'type' => 'varchar'),
					'company_kana'  => array('constraint' => 100, 'type' => 'varchar'),
					'pref'          => array('constraint' => 4,   'type' => 'varchar'),
					'city'          => array('constraint' => 100, 'type' => 'varchar'),
					'town'          => array('constraint' => 100, 'type' => 'varchar'),
					'company'       => array('constraint' => 100, 'type' => 'varchar'),
					'type'          => array('constraint' => 4,   'type' => 'varchar'),
				),
				array(),
				false,
				'InnoDB',
				'utf8_general_ci'
			);
			\DB::query("ALTER TABLE ".$table_name." ADD INDEX (zipcode)")->execute();
		}
	}


	// CSVを読み込みDBへ格納
	public static function insert_zipdata($type = '')
	{

		// CSV読み込み
		$file = APPPATH.'modules/zipcode2address/data/'.self::$csv_files[$type];
		$fp = fopen($file, 'r');
		if ( $type === 'base' )
		{
			// 一旦クリア
			\DB::query("DELETE FROM ".self::$_table_name." WHERE type = '".$type."'")->execute();

			// 基本データ
			$sql = '';
			while ( ($row = fgetcsv($fp)) !== FALSE ) {
				mb_convert_variables('UTF-8', 'SJIS-win', $row);
				// 半角カタカナ撲滅
				$row[3] = mb_convert_kana($row[3], 'KV');
				$row[4] = mb_convert_kana($row[4], 'KV');
				$row[5] = mb_convert_kana($row[5], 'KV');
				// DB登録
				$query = \DB::insert(self::$_table_name)->set(array(
					'zipcode'      => $row[2],
					'pref_kana'    => $row[3],
					'city_kana'    => $row[4],
					'town_kana'    => $row[5],
					'pref'         => $row[6],
					'city'         => $row[7],
					'town'         => $row[8],
					'type'         => $type,
					'company_kana' => '',
					'company'      => '',
				))->execute();
			}
		}
		else
		{
			// 一旦クリア
			\DB::query("DELETE FROM ".self::$_table_name." WHERE type = '".$type."'")->execute();
			
			// 事業所データ
			while ( ($row = fgetcsv($fp)) !== FALSE ) {
				mb_convert_variables('UTF-8', 'SJIS-win', $row);
				// 半角カタカナ撲滅
				$row[1] = mb_convert_kana($row[1], 'KV');
				// DB登録
				$query = \DB::insert(self::$_table_name)->set(array(
					'zipcode'      => $row[7],
					'pref_kana'    => '',
					'city_kana'    => '',
					'town_kana'    => '',
					'company_kana' => $row[1],
					'pref'         => $row[3],
					'city'         => $row[4],
					'town'         => $row[5].$row[6],
					'company'      => $row[2],
					'type'         => $type,
				))->execute();
			}
		}
		fclose($fp);

		return true;
	}


	// 都道府県
	static $PREF = array(
		0  => '',
		1  => '北海道',
		2  => '青森県',
		3  => '岩手県',
		4  => '宮城県',
		5  => '秋田県',
		6  => '山形県',
		7  => '福島県',
		8  => '茨城県',
		9  => '栃木県',
		10 => '群馬県',
		11 => '埼玉県',
		12 => '千葉県',
		13 => '東京都',
		14 => '神奈川県',
		15 => '新潟県',
		16 => '富山県',
		17 => '石川県',
		18 => '福井県',
		19 => '山梨県',
		20 => '長野県',
		21 => '岐阜県',
		22 => '静岡県',
		23 => '愛知県',
		24 => '三重県',
		25 => '滋賀県',
		26 => '京都府',
		27 => '大阪府',
		28 => '兵庫県',
		29 => '奈良県',
		30 => '和歌山県',
		31 => '鳥取県',
		32 => '島根県',
		33 => '岡山県',
		34 => '広島県',
		35 => '山口県',
		36 => '徳島県',
		37 => '香川県',
		38 => '愛媛県',
		39 => '高知県',
		40 => '福岡県',
		41 => '佐賀県',
		42 => '長崎県',
		43 => '熊本県',
		44 => '大分県',
		45 => '宮崎県',
		46 => '鹿児島県',
		47 => '沖縄県',
	);

}
