#目次
* 1 [Time Checkerの使い方](https://bitbucket.org/tanakalabo/time_checker/wiki/Time%20Checker%E3%81%AE%E4%BD%BF%E3%81%84%E6%96%B9)
* 2 [http://192.168.11.120:3000](http://192.168.11.120:3000)
* 3 [セットアップ](https://bitbucket.org/tanakalabo/time_checker/wiki/Time%20Checker%E3%81%AE%E3%82%BB%E3%83%83%E3%83%88%E3%82%A2%E3%83%83%E3%83%97)
* 4 [画面サンプル](https://bitbucket.org/tanakalabo/time_checker/wiki/%E7%94%BB%E9%9D%A2%E3%82%B5%E3%83%B3%E3%83%97%E3%83%AB)
* 5 [バックアップ](https://bitbucket.org/tanakalabo/time_checker/wiki/%E3%83%90%E3%83%83%E3%82%AF%E3%82%A2%E3%83%83%E3%83%97)


# time_checker
〜研究室用勤怠管理システム〜
学生証の製造番号をICカードリーダで読み取り、個人を判別
ラズベリーパイでPHPサーバをたて、勤怠履歴のデータベースへアクセス
研究室LAN内で勤怠管理のホームページを閲覧可能
↓↓↓↓↓↓↓↓↓↓↓↓画面のキャプチャ





** [画面サンプル](https://bitbucket.org/tanakalabo/time_checker/wiki/%E7%94%BB%E9%9D%A2%E3%82%B5%E3%83%B3%E3%83%97%E3%83%AB) **

##デバッグのためのサーバ起動方法
１．ターミナルでindex.phpのあるフォルダ内に入る

２．ipアドレスを調べてコピー
　　$ ip a
   eth0のinetに続く部分（デフォルトでは、10.0.2.15）

３．サーバ起動
　　$ php -S 10.0.2.15:3000
   ポート番号は使用可能なポートを選択

*ホストOS側のブラウザ等でページを見たい場合は、Vagrantfileに設定の記述が必要
config.vm.network "forwarded_port", guest: 3000, host:3000
編集後再起動が必要
vagrant reload

データベース情報
SQLite3


###もし他の端末からPHPビルトインサーバーにアクセスしたい場合はVagrantfileを編集する
`Vagrantfile`
```
# コメントを外す
config.vm.network "public_network"
```

vagrantをリロード
リロード中に入力を要求されるので`1`を入力
```
$ vagrant reload
...
1) en0: Wi-Fi (AirPort)
2) p2p0
3) awdl0
4) en2: Thunderbolt 1
5) en1: Thunderbolt 2
6) bridge0
==> default: When choosing an interface, it is usually the one that is
==> default: being used to connect to the internet.
    default: Which interface should the network bridge to? 1                  ←ここ
...
```

`$ ip a`でIPアドレスを確認して
```
$ php -S 192.168.xxx.xxx:3000
```

接続したい他の端末のブラウザで
`http://192.168.xxx.xxx:3000`
を入力すると見れる


##使用DB
SQLite3の
インストールはUbuntu上で
$sudo apt-get install sqlite3

databaseの仕様はwikiを参照


#＜開発環境＞
ubuntu18.04.1
PHP 7.3.5
SQLite3 3.22.0
python2系
nfcpy

###python の環境は
nfcpy を利用するため
python2.X系を使う


