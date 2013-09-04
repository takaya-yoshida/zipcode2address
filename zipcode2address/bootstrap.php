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

\Autoloader::add_core_namespace('Zipcode2address');

\Autoloader::add_classes(array(
	'Model_Zipcodedata' => APPPATH.'modules/zipcode2address/classes/model/zipcodedata.php',
));
