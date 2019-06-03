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
    sid = sys.argv[2]
    name = sys.argv[3]
    values = (idm, name, sid)
    c.execute("INSERT INTO members VALUES (?,?,?)", values)
    print(idm)
    print(sid)
    print(name)
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
    #print("error : idm is not exist in members table")
    return False

# status テーブルの中に sid が
# 存在すれば     (sid, name, in_time)
# 存在しなければ false
def search_sid(sid):
    sql = 'select sid,name,in_time from status where sid="' + str(sid) + '"'
    for row in c.execute(sql):
        return row
    return False






#１メンバー登録
#IDmと名前、学籍番号をデータベースに登録し紐付ける
# members(idM TEXT, name TEXT, sid INTEGER)
#FeliCaをタッチして学籍番号と前を入力
#print("\n[[[Sign up]]]")
conn = sqlite3.connect('../../server/db/data.db')
c = conn.cursor()
# idm = input(">>> FeliCa IDm : ")
idm = sys.argv[1]
insert_members(idm)
#print("\n[member table]")
#select_table("members")
conn.commit()
conn.close()
