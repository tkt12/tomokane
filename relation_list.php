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
        <h2 class="title">関係性編集</h2>
        <a class="backBtn" href="setting.php">閉じる</a>
    </header>
    <main>
        <div class="marginTop150">
        <?php
            require_once dirname(__FILE__) . '/functions.php';
            $pdo = connect();
            $datas = getRelation($pdo);
            $table = "relations";
            $column = "relation";
            foreach ($datas as $row) {
                $relation = $row["relation"];
                $page_link = "relation_list.php";
                $title = "関係性";
                // データを連結して 1 つの文字列にする
                $encoded_data = $table . "," . $column . "," . $relation . "," . $page_link . "," . $title;
                // nameカラムだけ表示し、編集リンクを作成、各データのnameを渡す
                echo "<p class='box'><a class='link' href='edit.php?data=" . $encoded_data . "'>" . $relation . "<img src='icon/chevron_right.svg'></a></p>";
            }
        ?>
        </div>
        <p class="box marginTop"><a class="add" href="relation_add.php">関係性追加</a></p>
    </main>
</body>
</html>