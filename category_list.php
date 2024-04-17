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
        <h2 class="title">カテゴリー編集</h2>
        <a class="backBtn" href="setting.php">閉じる</a>
    </header>
    <main>
        <div class="marginTop150">
        <?php
            require_once dirname(__FILE__) . '/functions.php';
            $pdo = connect();
            $datas = getCategory($pdo);
            $table = "categorys";
            $column = "category";
            foreach ($datas as $row) {
                $category = $row["category"];
                $page_link = "category_list.php";
                $title = "カテゴリー";
                // データを連結して 1 つの文字列にする
                $encoded_data = $table . "," . $column . "," . $category . "," . $page_link . "," . $title;
                // nameカラムだけ表示し、編集リンクを作成、各データのnameを渡す
                echo "<p class='box'><a class='link' href='edit.php?data=" . $encoded_data . "'>" . $category . "<img src='icon/chevron_right.svg'></a></p>";
            }
        ?>
        </div>
        <p class="box marginTop"><a class="add" href="category_add.php">カテゴリー追加</a></p>
    </main>
</body>
</html>