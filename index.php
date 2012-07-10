<?php
    $datapath = dirname(__FILE__)."/data/";

    if(!is_dir($datapath)){
        echo "データフォルダがありません。以下のコマンドで作ってください<br />";
        echo "mkdir {$datapath}"; 
        exit;
    }
    $cmd = "find {$datapath} | xargs egrep -n -i '(jpeg|jpg|png|gif)'";
    exec($cmd,$result);
    $data = array();
    foreach($result as $line){
	    if(substr_count($line,"バイナリー・ファイル")) continue;
		
	    $line_array = explode(":",$line);
	    $file = $line_array[0];
	    unset($line_array[0]);
	    $row = $line_array[1];
	    unset($line_array[1]);
            $text = join(":",$line_array);
	    $file = str_replace($datapath,"",$file);
		$text = htmlspecialchars($text);
		$text = preg_replace("/(jpeg|jpg|png|gif)/i",'<span class="red">${1}</span>',$text);
		$text = preg_replace("/(http)/i",'<span class="blue">${1}</span>',$text);
	    $data[$file][$row] = $text;
    }

?>
<html>
<head>
<style type="text/css">
<!--
body {font-family:monospace; padding: 0px; margin: 0px;}
h1{ background-color: #b0c4de; color: #FFFFFF; padding: 10px;}
table {
    border: 1px #CCCCCC solid;
    border-collapse: collapse;
    border-spacing: 0;
}
table tr {
}
table tr:hover {
	background-color: #f0f8ff; 
}

table th {
    padding: 5px;
    border: 1px #999999 solid;
    white-space: nowrap;
}
table td {
    padding: 5px;
    border: 1px #999999 solid;
}
.red{ color:#FF0000; font-weight: bold;}
.blue{ color:#0000FF; font-weight: bold;}
.page{ padding: 10px }
.padding10{ padding: 5px 10px;}
-->
</style>
</head>
<body>
<h1>画像チェック</h1>
指定フォルダ内に置いたファイルから、jpg,jpeg,png,gifの表記を探します。
<div class="page">
<h2>該当ファイル</h2>
<div class="padding10">
<div class="info">今回のチェック対象ファイルです。ちゃんとすべて網羅できているかを確認してください。</div>
<ul>
<?php
    foreach($data as $filename => $file){
	echo "<li>{$filename}</li>";
    }
?>
</ul>
</div>
<h2>検出箇所</h2>
<div class="padding10">
<?php
    foreach($data as $filename => $file){
		echo "<h3>{$filename}</h3>";
		echo "<table>";
        foreach($file as $line => $text){
			echo "<tr><th>";
			echo $line."行目";
			echo "</th><td>";
			echo $text;

			echo "</td></tr>";
        }	
		echo "</table>";
    }

?>
</div>

</div>
</body>
</html>
