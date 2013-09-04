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

class Controller_Search extends \Controller_Rest
{

	public function post_index()
	{
		$json = array(
			'res'   => 'NG',
			'error' => '郵便番号に該当する住所は見つかりませんでした。',
			'data'  => ''
		);

		// 郵便番号
		$zip1 = mb_convert_kana(\Input::post('zip1', ''), 'n');
		$zip2 = mb_convert_kana(\Input::post('zip2', ''), 'n');
		$zipcode = $zip1.$zip2;
		
		if ( strlen($zipcode) !== 7 )
		{
			return $this->response($json);
		}

		// 住所検索
		$result = Model_Zipcodedata::search($zipcode);

		if ( ! $result )
		{
			return $this->response($json);
		}

		// jsonで結果出力
		$json['res']   = 'OK';
		$json['error'] = '';
		$json['data']  = $result;
		$this->response($json);
	}
}
