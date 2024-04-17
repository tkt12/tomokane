<?php
declare(strict_types=1);

require_once dirname(__FILE__) . '/functions.php';

// エラーレポーティングのレベルを設定する
error_reporting(E_ALL & ~E_WARNING);

// フォームが送られてきたら実行する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームのデータを取得する
    $table = $_POST['table'];
    $page_link = $_POST['page_link'];
    
    // カラムと条件の配列を取得する
    $columns = $_POST['columns'];
    $conditions = $_POST['conditions'];
    $newValues = $_POST['newValues'];

    // SQLクエリを実行してデータを更新する
    $pdo = connect();
    
    // エラーメッセージの初期化
    $error_message = '';

    // 各行のデータを更新する
    foreach ($columns as $key => $columnArray) {
        foreach ($columnArray as $index => $column) {
            // $column、$condition、および$newValueをループ内で定義する
            $condition = $conditions[$key][$index];
            $newValue = $newValues[$key][$index];
            // updateData() を呼び出して更新
            try {
                updateData($pdo, $table, $column, $newValue, $condition);
            } catch (Exception $e) {
                // エラーメッセージを設定する
                $error_message = '同じ名前のデータが存在するため、更新できません。';
                // エラーが発生したため、ループを抜ける
                break 2;
            }
        }
    }
    
    // エラーメッセージがあれば表示する
    if (!empty($error_message)) {
        echo '<p>' . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . '</p>';
    } else {
        // 元のページにリダイレクトする
        header("Location: $page_link");
        exit(); // リダイレクト後にスクリプトの実行を終了する
    }
}

// データを取得して分割する
if(isset($_GET["data"])) {
    $encoded_data = $_GET["data"]; // "data" をキーとしてデータを取得する
    list($table, $column, $condition, $page_link, $title) = explode(",", $encoded_data); // explode()で変数内の文字列を特定の文字で分割
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
    <?php
        echo "<h2 class='title'>" . $title . "編集</h2>";
        echo "<a class='backBtn' href='" . $page_link . "'>キャンセル</a>";
    ?>

    </header>
    <main>
        <?php
            $pdo = connect();
            $datas = getEditData($pdo, $table, $column, $condition);

            // $datas が 1 つの場合でも、配列にする
            if (!is_array($datas)) {
                $datas = array($datas);
            }

            echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
            echo "<input type='hidden' name='table' value='$table'>";
            
            // $datasの各要素ごとにフォームのhiddenフィールドを作成する
            foreach ($datas as $key => $data) {
                // $dataの各カラムとその値に対してhiddenフィールドを作成する
                foreach ($data as $column => $condition) {
                    echo "<input type='hidden' name='columns[$key][]' value='$column'>";
                    echo "<input type='hidden' name='conditions[$key][]' value='$condition'>";
                    if($column != "color"){
                        echo "<div class='box marginTop150'><input class='paddingRight' type='text' value='$condition' name='newValues[$key][]' required></div>"; // 更新した
                    }else{
                        echo "<div class='box colorBox'><p>ラベルの色</p><input type='color' value='$condition' name='newValues[$key][]' required></div>"; // 更新した
                    }
                }
            }
            echo "<input type='hidden' name='page_link' value='$page_link'>"; // 飛んできたページのリンク
            echo "<input class='submit' type='submit' value='保存'>";
            echo "</form>";
        ?>
    </main>
    <footer>
        <a class='delete' href="javascript:void(0);" onclick="confirmDelete('<?php echo $table; ?>', '<?php echo $column; ?>', '<?php echo $condition; ?>', '<?php echo $page_link; ?>');">このデータを削除する</a>
    </footer>
    <script>
        function confirmDelete(table, column, condition, page_link) {
            if (confirm("本当にこのデータを削除しますか？")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var response = xhr.responseText;
                            alert(response); // レスポンスをアラートで表示
                            if (response.trim() === "データが正常に削除されました。") {
                                window.location.href = page_link; // 削除成功時のみリダイレクト
                            }
                        } else {
                            alert("削除中にエラーが発生しました。");
                        }
                    }
                };
                xhr.send("table=" + table + "&column=" + column + "&condition=" + condition + "&page_link=" + page_link);
            }
        }
    </script>
</body>
</html>
