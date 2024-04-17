<?php
declare(strict_types=1);

require_once dirname(__FILE__) . '/functions.php';

// フォームのデータを取得する
$id = $_POST['id'];

// SQLクエリを実行してデータを削除する
$pdo = connect();
$result = deleteTimeline($pdo, $id);

// 削除操作の結果をクライアントに返す
if ($result) {
    echo "データが正常に削除されました。";
} else {
    echo "削除できませんでした。";
}

exit();
?>