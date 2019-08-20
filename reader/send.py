# -*- coding: utf-8 -*-
# コマンドラインでsql文を受け取ってスプレッドシートに送信

import requests
import sys
args = sys.argv

sql = str(args[0])
url = "https://script.google.com/macros/s/AKfycbxbAUD26YExWvN6SMr805EakST0tJA2T4MqU8pBudHGskHGw1Q/exec?sql=" + sql
requests.get(url)