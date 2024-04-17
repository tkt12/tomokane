<?php
    declare(strict_types=1);
    // フォームが送られてきたら実行する
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        try {
            require_once dirname(__FILE__) . '/functions.php';

            $genre = $_POST['genre'];
            $amoment = escape($_POST['amoment']);
            $name = $_POST['name'];
            $relation = $_POST['relation'];
            $category = $_POST['category'];
            $ymd = $_POST['ymd'];
            $memo = escape($_POST['memo']);
            
            $pdo = connect();
            $sql = "INSERT INTO datas (genre, amoment, name, relation, category, ymd, memo) VALUES (:genre, :amoment, :name, :relation, :category, :ymd, :memo)"; // テーブルに登録するINSERT INTO文を変数に格納　VALUESはプレースフォルダーで空の値を入れとく
            $stmt = $pdo->prepare($sql); //値が空のままSQL文をセット
            $params = array(':genre' => $genre, ':amoment' => $amoment, ':name' => $name, ':relation' => $relation, ':category' => $category, ':ymd' => $ymd, ':memo' => $memo); // 挿入する値を配列に格納
            $stmt->execute($params); //挿入する値が入った変数をexecuteにセットしてSQLを実行
            
            header('Location:borrow.php'); // フォームを再送信されないようにページ更新を挟む
            exit;

        } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
        }
        // フォームが送信されたことをセッションに記録
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
        <div class="genre">
            <ul>
                <li class="genreF"><a href="index.php">貸付</a></li>
                <li class="genreT"><a href="borrow.php">借入</a></li>
            </ul>
        <div>
    </header>
    <main>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <input type="hidden" value="借入" name="genre"> 
            <table class="boxTop">
                <tr class="box">
                    <th>金額</th>
                    <td>
                        <input type="text" name="amoment" placeholder="10000" required>
                    </td>
                </tr>
            </table>
            <table>
                <tr class="box">
                    <th>名前</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'names';
                            $pulldownData = getPulldownData($pdo, $table);
                            // プルダウンメニューのデータが取得できた場合のみ、プルダウンメニューを生成する
                            if (!empty($pulldownData)) {
                                echo '<select name="name">';
                                foreach ($pulldownData as $row) {
                                    // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                    echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                }
                                echo '</select>';
                            } else {
                                // プルダウンメニューのデータが取得できなかった場合のエラー処理
                                echo 'プルダウンメニューのデータを取得できませんでした。';
                            }
                        ?>
                    </td>
                </tr>
                <tr class="box">
                    <th>関係性</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'relations';
                            $pulldownData = getPulldownData($pdo, $table);
                            // プルダウンメニューのデータが取得できた場合のみ、プルダウンメニューを生成する
                            if (!empty($pulldownData)) {
                                echo '<select name="relation">';
                                foreach ($pulldownData as $row) {
                                    // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                    echo '<option value="' . htmlspecialchars($row['relation']) . '">' . htmlspecialchars($row['relation']) . '</option>';
                                }
                                echo '</select>';
                            } else {
                                // プルダウンメニューのデータが取得できなかった場合のエラー処理
                                echo 'プルダウンメニューのデータを取得できませんでした。';
                            }
                        ?>
                    </td>
                </tr>
                <tr class="box">
                    <th>カテゴリー</th>
                    <td>
                        <?php
                            require_once dirname(__FILE__) . '/functions.php';

                            $pdo = connect();
                            $table = 'categorys';
                            $pulldownData = getPulldownData($pdo, $table);
                            // プルダウンメニューのデータが取得できた場合のみ、プルダウンメニューを生成する
                            if (!empty($pulldownData)) {
                                echo '<select name="category">';
                                foreach ($pulldownData as $row) {
                                    // データベースから取得した情報を元に、プルダウンメニューのオプションを生成する
                                    echo '<option value="' . htmlspecialchars($row['category']) . '">' . htmlspecialchars($row['category']) . '</option>';
                                }
                                echo '</select>';
                            } else {
                                // プルダウンメニューのデータが取得できなかった場合のエラー処理
                                echo 'プルダウンメニューのデータを取得できませんでした。';
                            }
                        ?>
                    </td>
                </tr>
                <tr class="box">
                    <th>日付</th>
                    <td><input type="date" name="ymd" required></td>
                </tr>
                <tr class="box">
                    <th>メモ</th>
                    <td><input type="text" name="memo" placeholder="状況や期限"></td>
                </tr>
            </table>
            <input class="submit" type="submit" value="保存">
        </form>
    </main>
    <footer>
        <ul class="menu">
            <li><a href="index.php" class="menuT"><img src="icon/pen_b.svg" alt="入力アイコン">入力</a></li>
            <li><a href="timeline.php" class="menuF"><img src="icon/timeline_g.svg" alt="タイムラインアイコン">タイムライン</a></li>
            <li><a href="setting.php" class="menuF"><img src="icon/setting_g.svg" alt="設定アイコン">設定</a></li>
        </ul>
    </footer>
</body>
</html