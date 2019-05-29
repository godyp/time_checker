# -*- coding: utf-8 -*-
import os
import sys
sys.path.append('/home/pi/.local/lib/python2.7/site-packages')
import binascii
import nfc
import time
from threading import Thread, Timer
import datetime
import sqlite3
# import RPi.GPIO as GPIO

#GPIOのピン設定
# RED = 25
# GREEN = 24

# FeliCa待ち受けの1サイクル秒
TIME_cycle = 1.0
# FeliCa待ち受けの反応インターバル秒
TIME_interval = 0.2
# タッチされてから次の待ち受けを開始するまで無効化する秒
TIME_wait = 3

# def light_up(color):
#     GPIO.setmode(GPIO.BCM)
#     GPIO.setup(color, GPIO.OUT)

#     GPIO.output(color, GPIO.HIGH)
#     time.sleep(0.5)
#     GPIO.output(RED, GPIO.LOW)
#     time.sleep(0.5)
#     GPIO.output(color, GPIO.HIGH)
#     time.sleep(0.5)
#     GPIO.output(RED, GPIO.LOW)
#     time.sleep(0.5)

#     GPIO.cleanup()

def felica_waiting():
    # NFC接続リクエストのための準備
    # 212F(FeliCa)で設定
    target_req_felica = nfc.clf.RemoteTarget("212F")
    # 0003(Suica)
    # target_req_felica.sensf_req = bytearray.fromhex("0000030000")

    #print 'FeliCa waiting...'
    while True:
        # USBに接続されたNFCリーダに接続してインスタンス化
        clf = nfc.ContactlessFrontend('usb')
        # Suica待ち受け開始
        # clf.sense( [リモートターゲット], [検索回数], [検索の間隔] )
        target_res = clf.sense(target_req_felica, iterations=int(TIME_cycle//TIME_interval)+1 , interval=TIME_interval)

        if target_res != None:

            #tag = nfc.tag.tt3.Type3Tag(clf, target_res)
            #なんか仕様変わったっぽい？↓なら動いた
            tag = nfc.tag.activate_tt3(clf, target_res)
            tag.sys = 3

            #IDmを取り出す
            idm = binascii.hexlify(tag.idm)
            #print 'FeliCa detected. idm = ' + idm
            light_up(GREEN)
            clf.close()
            return idm

        #end if

        clf.close()

    #end while
    return -1




idm = felica_waiting()
print(idm)

