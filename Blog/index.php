<?php
	require_once('./src/common/DBUtil.php');

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

	//ブログ内容の取得
	$DBUtil->setQuery("SHOW_BLOG", $startDate);
	$stmt = &$DBUtil->getStmt();
	if($stmt->bind_result($id, $title, $content, $insdate, $goodCnt)){//結果のバインド
		while ($stmt->fetch()) {
			$db_data[$rownum]["id"]  = $id;
			$db_data[$rownum]["title"]  = $title;
			$db_data[$rownum]["content"] = $content;
			$db_data[$rownum]["insdate"]  = $insdate;
			$db_data[$rownum]["goodCnt"]  = $goodCnt;
			$rownum++;
		}
		$startDate = str_replace("/", "", $db_data[$rownum-1]["insdate"]) ;
	}
	$stmt->close();

	//startDateより前の日付のブログがあるか
	$DBUtil->setQuery("SHOW_BLOG_IS_NEXT", $startDate);
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

<body onbeforeunload="ajaxExec()">
	<div id="wrapper">
		<div id="SidePartner" style="float:left;">
			<font color="red">まだオモチャ</font>
			<div class="SideHeader"><?php echo date("Y/m/d(D)"); ?></div>
			<div class="SideBody">Good:<span id="0goodCount">0</span>/bad:<span id="badCount">0</span></div>
		</div>
		<!-- <img alt="test" src="./img/icon/test.png"onclick="ajaxExec()"> -->
		<?php if($frontFLG == 1){?>
			<form name="frontH" method="POST" action=<?php __FILE__ ?>>
				<input type=hidden name="starDate"value=<?php echo $startDate;?>>
				<a href="javascript:document.frontH.submit()">過去の20件を表示</a>
			</form>
		<?php }?>
		<div class = 'DaysContent'><?php
		for($i=0;$i<count($db_data);$i++){;?>
			<div class = 'Day'>
				<div class = 'TitleLine'>
					<span class="TITLE"><?php echo $db_data[$i]['title']; ?></span>
					<span class="INSDATE"><?php echo $db_data[$i]['insdate']; ?></span>
				</div>
				<span class="CONTENT"><?php echo $db_data[$i]['content']; ?></span><br>
				<div id=<?php echo $db_data[$i]['id']; ?>>
					<img alt="いいね" src="./img/icon/good0.png"onclick="goodClick('<?php echo $db_data[$i]['id']; ?>')">
					<input type="hidden" value="0">
					<span id=<?php echo $db_data[$i]['id']. "_goodCnt";?>><?php echo $db_data[$i]["goodCnt"];?></span>
				</div>
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
