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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>郵便番号→住所検索モジュール</title>
	<?php echo \Asset::css('bootstrap.css'); ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript" ></script>
	<style type="text/css">
		body{margin:10px;}
		h3{margin-bottom:30px;}
		.controls{margin-top:5px;}
		.zip1{width:40px;}
		.zip2{width:60px;}
		#pref_form select{width:100px;}
	</style>
</head>
<body>
<h3 style="">郵便番号→住所検索モジュール</h3>
<h4>住所連結バージョン</h4>
<form class="form-horizontal" id="concat_form">
	<div class="control-group">
		<label class="control-label">郵便番号</label>
		<div class="controls">
			<input type="text" class="zip1" name="zip1" value="" maxlength="3">
			-
			<input type="text" class="zip2" name="zip2" value="" maxlength="4">
			<button type="button" class="btn btn-small" id="btn_zipsearch"> 住所検索 </button>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">住所</label>
		<div class="controls">
			<input type="text" name="address" value="">
		</div>
	</div>
</form>

<h4>都道府県リストバージョン</h4>
<form class="form-horizontal" id="pref_form">
	<div class="control-group">
		<label class="control-label">郵便番号</label>
		<div class="controls">
			<input type="text" class="zip1" name="zip1" value="" maxlength="3">
			-
			<input type="text" class="zip2" name="zip2" value="" maxlength="4">
			<button type="button" class="btn btn-small" id="btn_zipsearch2"> 住所検索 </button>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">住所</label>
		<div class="controls">
			<?php echo \Form::select('pref', '', \Zipcode2address\Model_Zipcodedata::$PREF);?>
			<input type="text" name="address" value="">
		</div>
		<div class="controls">
			<input type="text" name="company" value="">
		</div>
	</div>
</form>

<?php echo render('maintenance');?>

<script type="text/javascript">
$(document).ready(function() {
	
	// 郵便番号→住所検索
	function zipsearch(conf){
		if( ! conf.zip1 || ! conf.zip2 )
		{
			alert('郵便番号正しく入力してください。');
			return false;
		}
		var label = conf.btn.html();
		conf.btn.html('検索中...').attr('disabled', true);
		$.ajax({
			url: "<?php echo Uri::create('zipcode2address/search.json');?>",
			data:{zip1:conf.zip1, zip2:conf.zip2},
			type:"POST"
		}).done(function(json){
			if(json.res == 'OK'){
				if( ! json.data ){
					alert(json.error);
				}else{
					conf.callback(json.data);
				}
			}else{
				alert(json.error);
			}
			conf.btn.html(label).attr('disabled', false);
		}).fail(function(json){
			alert('接続エラーが発生しました。');
			conf.btn.html(label).attr('disabled', false);
		});
	}

	// 検索イベント予約（都道府県＋住所バージョン）
	$('#btn_zipsearch').on('click', function(){
		zipsearch({
			zip1     : $('#concat_form input[name="zip1"]').val(),
			zip2     : $('#concat_form input[name="zip2"]').val(),
			btn      : $(this),
			callback : function(data){
				$('#concat_form input[name="address"]').val(data.pref + data.city + data.town);
			}
		});
	});

	// 検索イベント予約（都道府県リスト＆事業所名バージョン）
	$('#btn_zipsearch2').on('click', function(){
		zipsearch({
			zip1     : $('#pref_form input[name="zip1"]').val(),
			zip2     : $('#pref_form input[name="zip2"]').val(),
			btn      : $(this),
			callback : function(data){
				$('#pref_form select[name="pref"] option').attr('selected', false);
				$('#pref_form select[name="pref"] option').each(function(){
					if ( $(this).html() == data.pref )
					{
						$('#pref_form select[name="pref"]').val($(this).val());
					}
				});
				$('#pref_form input[name="address"]').val(data.city + data.town);
				$('#pref_form input[name="company"]').val(data.company);
			}
		});
	});

});
</script>

</body>
</html>