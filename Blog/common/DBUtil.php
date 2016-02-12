<?php
class DBUtil{

	protected $_mysqli;

	protected $_isSqlConnect;

	protected $_sqlconfig;

	protected $_query;

	private $_stmt;

	/*
	 * @queryName
	 * クエリの名前からクエリを取得し、
	 * セットする
	 */
	function setQuery($qname){
		$this->_query = $this->_sqlconfig[$qname];
	}

	/*
	 * ステートメントを取得する
	 */
	function getStmt(){
		return $this->_stmt;
	}
	/*
	 * コンストラクタ
	 * DBへの接続
	 * iniの取得を行う
	 */
	public function __construct(){
		$ini_DB = parse_ini_file(dirname(__DIR__) . "/includes/config.ini", true)["DB_CONNECTION"];
		//DB接続
		$this->_mysqli = new mysqli(base64_decode($ini_DB["DB_HOST"]) . base64_decode($ini_DB["DB_PORT"]), base64_decode($ini_DB["DB_USER"]),
				 				base64_decode($ini_DB["DB_PASSWORD"]), base64_decode($ini_DB["DB_NAME"]));

		if ($this->_mysqli->connect_error) {
			//エラー時
			echo $this->_mysqli->connect_error;
			exit();
		} else {
			$this->_isSqlConnect = true;
			$this->_mysqli->set_charset($ini_DB["DB_CHARSET"]);
			$this->_sqlconfig = parse_ini_file(dirname(__DIR__) . "/includes/SQL.ini");
		}
	}

	/*
	 * @Param[n]
	 * ステートメントの処理を進める
	 * executeまで行う
	 */
	function stmtExec(){
		$param = func_get_args();
		$b_param = array($this->param_type($param));//バインド変数のタイプ取得
		//バインド変数取得-参照渡しじゃないとだめ-
		foreach ($param as $value){
			$b_param[] = &$value;
		}

		if ($this->_stmt = $this->_mysqli->prepare($this->_query)){//QUERYの設定
			call_user_func_array(array($this->_stmt, "bind_param"),$b_param);
			if (!$this->_stmt->execute()){//SQLの実行
				echo "stmtExec:ERROR";
			}
		}
	}
	/*
	 * バインド変数のタイプ取得
	 */
	function param_type($v_param){
		$type = "";
		foreach ($v_param as $value) {
			switch (gettype($value)){
				case "integer":
					$type = $type . "i";
					break;
				case "double":
					$type = $type . "d";
					break;
				default:
					$type = $type . "s";
			}
		}
		return $type;
	}
}