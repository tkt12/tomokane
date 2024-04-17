<?php
declare(strict_types=1);

require_once dirname(__FILE__) . '/functions.php';

// エラーレポーティングのレベルを設定する
error_reporting(E_ALL & ~E_WARNING);

// エラーメッセージの初期化
$error_message = '';

// フォームが送られてきたら実行する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームのデータを取得する
    $relation = escape($_POST['relation']);

    // sql分のためにテーブルなどを指定
    $table = "relations";
    $column = "relation";

    // SQLクエリを実行してデータを更新する
    $pdo = connect();
    // insertR_C関数の呼び出し部分でエラーハンドリングを行う
    try {
        insertR_C($pdo, $table, $column, $relation);
        
        // データが正常に挿入された場合、リダイレクトする
        header("Location: relation_list.php");
        exit(); // リダイレクト後にスクリプトの実行を終了する
    } catch (Exception $e) {
        // エラーメッセージを設定する
        $error_message = '同じ名前のデータが存在するため、登録できません。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>友は金なり</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h2 class="title">関係性追加</h2>
        <p class="backBtn"><a href="relation_list.php">キャンセル</a></p>
    </header>
    <main>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="box marginTop150"><input class="paddingRight" type="text" name="relation" placeholder="関係性を入力" required></div>
            <input class="submit" type="submit" value="保存">
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
