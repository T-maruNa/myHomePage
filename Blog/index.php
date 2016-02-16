<?php
	require_once('./common/DBUtil.php');

	// 初期化
	$DBUtil = new DBUtil();
	unset($db_data);
	$rownum = 0;
	$frontFLG = 1;

	if(isset($_POST['starDate'])){
		$startDate = $_POST['starDate'];
	}else{
		$startDate ='29991231';
	}

	$DBUtil->setQuery("SHOW_BLOG");
	$DBUtil->stmtExec($startDate);
	$stmt = &$DBUtil->getStmt();
	if($stmt->bind_result($title, $content, $insdate)){//結果のバインド
		while ($stmt->fetch()) {
			$db_data[$rownum]["title"]  = $title;
			$db_data[$rownum]["content"] = $content;
			$db_data[$rownum]["insdate"]  = $insdate;
			$rownum++;
		}
		$startDate = str_replace("/", "", $db_data[$rownum-1]["insdate"]) ;
	}
	$stmt->close();

	$DBUtil->setQuery("SHOW_BLOG_IS_NEXT");
	$DBUtil->stmtExec($startDate);
	$stmt = &$DBUtil->getStmt();
	if($stmt->bind_result($cnt)){
		$stmt->fetch();//SQLの結果取得
		if($cnt<= 0){
			$frontFLG = 0;
		}
	}
	$stmt->close();

?>
<html>
<head>
	<?php include('./header.php'); ?>
</head>
<body>
	<div id="wrapper">
		<div id="SidePartner" style="float:left;">
			<font color="red">まだオモチャ</font>
			<div class="SideHeader"><?php echo date("Y/m/d(D)"); ?></div>
			<div class="SideBody">Good:<span id="goodCount">0</span>/bad:<span id="badCount">0</span></div>
		</div>
		<?php if($frontFLG == 1){?>
			<form name="frontH" method="POST" action=<?php __FILE__ ?>>
				<input type=hidden name="starDate"value=<?php echo $startDate;?>>
				<a href="javascript:document.frontH.submit()">過去の20件を表示</a>
			</form>
		<?php }?>
		<div class = 'DaysContent'><?php
		for($i=0;$i<count($db_data);$i++){?>
			<div class = 'Day'>
				<div class = 'TitleLine'>
					<span class="TITLE"><?php echo $db_data[$i]['title']; ?></span>
					<span class="INSDATE"><?php echo $db_data[$i]['insdate']; ?></span>
				</div>
				<span class="CONTENT"><?php echo $db_data[$i]['content']; ?></span><br>
				<?php if($db_data[$i]['insdate'] >= date("Y/m/d")){ ?>
				<input type="button"onclick="goodClick()"class="GoodClick"value="Good"><input type="button"onclick="badClick()" class="BadClick"value="Bad">
				<?php }?>
			</div>
			<?php
		}?></div>
		<?php if($frontFLG == 1){?>
			<form name=frontF method=POST action=<?php __FILE__ ?>>
				<input type=hidden name="starDate"value=<?php echo $startDate;?>>
				<a href="javascript:document.frontF.submit()">過去の20件を表示</a>
			</form>
		<?php }?>
	</div>
</body>
</html>
