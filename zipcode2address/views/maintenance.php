<h4>インストール方法</h4>
<ul>
	<li>コマンドラインにて以下の実行<br>
		全国一括：oil refine zipcode2address::import base<br>
		事業所：oil refine zipcode2address::import com<br>
		※oilがインストールされていない場合はコマンドの先頭に「php 」が必要です。
	</li>
</ul>

<h4>住所データのメンテナンス方法</h4>
<ul>
	<li>日本郵政の郵便番号データダウンロード（http://www.post.japanpost.jp/zipcode/dl/kogaki.html）から「全国一括」のCSVをダウンロード</li>
	<li>同じく事業所の個別郵便番号データ（http://www.post.japanpost.jp/zipcode/dl/jigyosyo/index.html）のCSVをダウンロード</li>
	<li>上記zipを解凍するとできるKEN_ALL.CSVとJIGYOSYO.CSVを /fuel/app/modules/zipcode2address/data/ の同ファイルに上書き</li>
	<li>上記インストールを同じ作業をする</li>
</ul>