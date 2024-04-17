<?php
    declare(strict_types=1);

    // フォームが送られてきたら実行する
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once dirname(__FILE__) . '/functions.php';

        $id = $_POST['id'];
        $genre = $_POST['genre'];
        $amoment = escape($_POST['amoment']);
        $name = $_POST['name'];
        $relation = $_POST['relation'];
        $category = $_POST['category'];
        $ymd = $_POST['ymd'];
        $memo = escape($_POST['memo']);
        
        $pdo = connect();

        try{
            timelineEdit($pdo, $id, $genre, $amoment, $name, $relation, $category, $ymd, $memo);
            
            // 元のページにリダイレクトする
            header("Location: timeline.php");
            exit(); // リダイレクト後にスクリプトの実行を終了する
        } catch (Exception $e) {
            // エラーメッセージを設定する
            $error_message = '更新できませんでした。';
        }
        // フォームが送信されたことをセッションに記録
    }
    
    // timeline_edit.php で送信されたデータを受け取る
    if (isset($_GET['id']) && isset($_GET['genre']) && isset($_GET['amoment']) && isset($_GET['name']) && isset($_GET['relation']) && isset($_GET['category']) && isset($_GET['date']) && isset($_GET['memo'])) {
        $id = $_GET['id'];
        $genre = $_GET['genre'];
        $amoment = $_GET['amoment'];
        $name = $_GET['name'];
        $relation = $_GET['relation'];
        $category = $_GET['category'];
        $ymd = $_GET['date'];
        $memo = $_GET['memo'];
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
        <?php echo "<div class='genreBox'><p>$genre</p><div>"; ?>
        <a class="backBtn" href="timeline.php">キャンセル</a>
</header>
    <main>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <input type="hidden" value="<?php echo $id; ?>" name="id"> 
            <input type="hidden" value="<?php echo $genre; ?>" name="genre"> 
            <table class="boxTop">
                <tr class="box">
                    <th>金額</th>
                    <td><input type="text" name="amoment" value="<?php echo $amoment; ?>" required></td>
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
                                    $selected = ($row['name'] == $name) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['name']) . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                                }
                                echo '</select>';
                            } else {
                                // プルダウンメニューのデータが取得できなかった場合のエラー処理
                                echo 'プルダウンメニューのデータを取得できませんでした。';
                            }                        ?>
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
                                    $selected = ($row['relation'] == $relation) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['relation']) . '" ' . $selected . '>' . htmlspecialchars($row['relation']) . '</option>';
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
                                    $selected = ($row['category'] == $category) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['category']) . '" ' . $selected . '>' . htmlspecialchars($row['category']) . '</option>';
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
                    <td><input type="date" name="ymd" value="<?php echo $ymd; ?>" required></td>
                </tr>
                <tr class="box">
                    <th>メモ</th>
                    <td><input type="text" name="memo" value="<?php echo $memo; ?>"></td>
                </tr>
            </table>
            <input class="submit" type="submit" value="保存">
        </form>
    </main>
    <footer>
        <a class="delete" href="javascript:void(0);" onclick="confirmDelete('<?php echo $id; ?>');">このデータを削除する</a>
    </footer>
    <script>
        function confirmDelete(id) {
            if (confirm("本当にこのデータを削除しますか？")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "deleteTimeline.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var response = xhr.responseText;
                            alert(response); // レスポンスをアラートで表示
                            if (response.trim() === "データが正常に削除されました。") {
                                window.location.href = 'timeline.php'; // 削除成功時のみリダイレクト
                            }
                        } else {
                            alert("削除中にエラーが発生しました。");
                        }
                    }
                };
                xhr.send("id=" + id);
            }
        }
    </script>
</body>
</html>