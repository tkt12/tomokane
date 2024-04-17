-- データベース：TomoKane

-- TomoKaneデータべースの作成
DROP DATABASE IF EXISTS tomokane;
CREATE DATABASE tomokane;

USE tomokane;

-- データ（datas）テーブル削除
DROP TABLE IF EXISTS datas;

-- 名前（names）テーブル削除
DROP TABLE IF EXISTS names;

-- 関係性（relations）テーブル削除
DROP TABLE IF EXISTS relations;

-- カテゴリー（categorys）テーブル削除
DROP TABLE IF EXISTS categorys;

-- 名前（names）テーブル作成
CREATE TABLE names (name VARCHAR(30) NOT NULL,color VARCHAR(30) NOT NULL,PRIMARY KEY(name)
);

-- 関係性（relations）テーブル作成
CREATE TABLE relations (relation VARCHAR(10) NOT NULL,PRIMARY KEY(relation)
);

-- カテゴリー（categorys）テーブル作成
CREATE TABLE categorys (category VARCHAR(10) NOT NULL,PRIMARY KEY(category)
);

-- データ（datas）テーブル作成
CREATE TABLE datas (id INT AUTO_INCREMENT PRIMARY KEY,genre VARCHAR(2) NOT NULL,amoment INT(9) NOT NULL,name VARCHAR(30) NOT NULL,relation VARCHAR(10) NOT NULL,category VARCHAR(10) NOT NULL,ymd DATE NOT NULL,memo TEXT,FOREIGN KEY (name) REFERENCES names(name),FOREIGN KEY (relation) REFERENCES relations(relation),FOREIGN KEY (category) REFERENCES categorys(category)
);

-- 名前（names）テーブル　データ挿入
INSERT INTO names VALUES('山田　太郎','#FAD03E');
INSERT INTO names VALUES('奥村　晴久','#FA3EC5');
INSERT INTO names VALUES('東　竜生','#029B11');

-- 関係性（relations）テーブル　データ挿入
INSERT INTO relations VALUES('友達');
INSERT INTO relations VALUES('恋人');
INSERT INTO relations VALUES('同僚');

-- カテゴリー（categorys）テーブル データ挿入
INSERT INTO categorys VALUES('ご飯');
INSERT INTO categorys VALUES('タクシー');
INSERT INTO categorys VALUES('コンビニ');

-- データ（datas）テーブル　データ挿入
INSERT INTO datas (genre, amoment, name, relation, category, ymd, memo) VALUES('貸付',10000,'山田　太郎','同僚','タクシー','2024-02-10',NULL);
INSERT INTO datas (genre, amoment, name, relation, category, ymd, memo) VALUES('貸付',950,'奥村　晴久','友達','ご飯','2024-02-07','じゃん負け奢り');
INSERT INTO datas  (genre, amoment, name, relation, category, ymd, memo)VALUES('借入',530,'奥村　晴久','友達','コンビニ','2024-01-31',NULL);
INSERT INTO datas (genre, amoment, name, relation, category, ymd, memo) VALUES('借入',530,'東　竜生','友達','コンビニ','2024-01-31',NULL);
