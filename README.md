# time_checker

phpの起動の仕方
仮想環境内のターミナルでindex.phpのフォルダに入る。
$ ip -a
でipアドレスをコピーする。


phpの簡易サーバーを起動
$ php -S xxx.xxx.xxx.xxx:8000
PHP 7.3.5-1+ubuntu18.04.1+deb.sury.org+1 Development Server started at Fri May 24 04:01:37 2019
Listening on http://xxx.xxx.xxx.xxx:8000
Document root is /home/vagrant/share/time_checker/time_checker
Press Ctrl-C to quit.

3行目のURLをコピーして、ブラウザに貼ると表示される

phpを起動しなくても
拡張子を.htmlに変えるとブラウザで開ける

※
KIT-IA
KIT-IB
の学内LANでは起動できないかも？