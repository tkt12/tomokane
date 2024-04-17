<?php
declare(strict_types=1);

require_once dirname(__FILE__) . '/functions.php';

// フォームのデータを取得する
$table = $_POST['table'];
$column = $_POST['column'];
$condition = $_POST['condition'];

// SQLクエリを実行してデータを削除する
$pdo = connect();
$result = deleteData($pdo, $table, $column, $condition);

// 削除操作の結果をクライアントに返す
if ($result) {
    echo "データが正常に削除されました。";
} else {
    echo "このデータを使用した登録データが存在するため、削除できませんでした。";
}

exit();
?>