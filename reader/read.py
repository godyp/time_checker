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

# FeliCa待ち受けの1サイクル秒
TIME_cycle = 1.0
# FeliCa待ち受けの反応インターバル秒
TIME_interval = 0.2
# タッチされてから次の待ち受けを開始するまで無効化する秒
TIME_wait = 3

def felica_waiting():
    # NFC接続リクエストのための準備
    # 212F(FeliCa)で設定
    target_req_felica = nfc.clf.RemoteTarget("212F")
    # 0003(Suica)
    # target_req_felica.sensf_req = bytearray.fromhex("0000030000")

    print 'FeliCa waiting...'
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
            print 'FeliCa detected. idm = ' + idm
            return idm

            time.sleep(TIME_wait)
        #end if

        clf.close()

    #end while
    return -1

# tableを表示する
def select_table(table):
    sql = 'select * from "' + str(table) + '"'
    for row in c.execute(sql):
        print(row)

# members テーブルに新規登録する
# 登録できれば          true
# すでに存在していれば  false
def insert_members(idm):
    if search_idm(idm) != False:
        print("error : Already exist in members table")
        return False
    values = ()
    sid = raw_input(">>> student number : ")
    name = raw_input(">>> name : ")
    values = (idm, name, sid)
    c.execute("INSERT INTO members VALUES (?,?,?)", values)
    return True

# status テーブルに行を追加
def record_in_time(row_mem):
    in_time = datetime.datetime.now()
    values = row_mem + (in_time,)
    c.execute("INSERT INTO status VALUES (?,?,?)", values)

# status テーブルから行を削除し、history テーブルに行を追加する
def record_out_time(row_sts):
    out_time = datetime.datetime.now()
    values = row_sts + (out_time,)
    c.execute("INSERT INTO history VALUES (?,?,?,?)", values)
    sql = 'delete from status where sid="' + str(sid) + '"'
    c.execute(sql)

# status テーブルの中に sid が
# 存在すれば     true
# 存在しなければ false
def check_intime(sid):
    sql = 'select in_time from status where id="' + str(sid) + '"'
    for row in c.execute(sql):
        return True
    return False

# members テーブルの中に idm が
# 登録されていれば      (sid, name)
# 登録されていなければ  false
def search_idm(idm):
    sql = 'select sid,name from members where idm="' + str(idm) + '"'
    for row in c.execute(sql):
        return row
    print("error : idm is not exist in members table")
    return False

# status テーブルの中に sid が
# 存在すれば     (sid, name, in_time)
# 存在しなければ false
def search_sid(sid):
    sql = 'select sid,name,in_time from status where sid="' + str(sid) + '"'
    for row in c.execute(sql):
        return row
    return False



######################################################################################################################################################




#１メンバー登録
#IDmと名前、学籍番号をデータベースに登録し紐付ける
# members(idM TEXT, name TEXT, sid INTEGER)
#FeliCaをタッチして学籍番号と前を入力
print("\n[[[Sign up]]]")
conn = sqlite3.connect('../server/db/data.db')
c = conn.cursor()
# idm = input(">>> FeliCa IDm : ")
idm = felica_waiting()
insert_members(idm)
print("\n[member table]")
select_table("members")
conn.commit()
conn.close()



#２打刻
#学籍番号、名前、入室時間、体質時間の登録
# status(sid INTEGER, name TEXT, in_time TEXT)
# history(sid INTEGER, name TEXT, in_time TEXT, out_time TEXT)
while True:
    while idm != "exit":
        conn = sqlite3.connect('../server/db/data.db')
        c = conn.cursor()
        #FeliCaがかざされたら実行
        # idm = input("\n>>> FeliCa IDm : ")
        idm = felica_waiting()
        # idm が登録されていなければ初めからやり直しにする
        row_mem = search_idm(idm)
        if row_mem == False:
            break

        # in_time が存在しなければ in_time を記録する
        sid, name = row_mem
        row_sts = search_sid(sid)
        if row_sts == False:
            record_in_time(row_mem)
        else:
            record_out_time(row_sts)

        #それぞれのテーブルを確認する
        print("\n[member table]")
        select_table("members")
        print("\n[status table]")
        select_table("status")
        print("\n[history table]")
        select_table("history")


        conn.commit()

        conn.close()


    #１メンバー登録
    #IDmと名前、学籍番号をデータベースに登録し紐付ける
    # members(idm TEXT, name TEXT, sid TEXT)
    #FeliCaをタッチして学籍番号と前を入力
    print("\n[[[Sign up]]]")
    conn = sqlite3.connect('../server/db/data.db')
    c = conn.cursor()
    # idm = input(">>> FeliCa IDm : ")
    idm = felica_waiting()
    insert_members(idm)
    print("\n[member table]")
    select_table("members")
    conn.commit()
    conn.close()