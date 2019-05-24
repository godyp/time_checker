# time_checker
デバッグのためのサーバ起動方法
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

使用DB
SQLite3の
インストールはUbuntu上で
$sudo apt-get install sqlite3


＜開発環境＞
ubuntu18.04.1
PHP 7.3.5
SQLite3 3.22.0
