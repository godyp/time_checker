# -*- coding: utf-8 -*-
import RPi.GPIO as GPIO
import time

RED = 25
GREEN = 24


GPIO.setmode(GPIO.BCM)
GPIO.setup(RED, GPIO.OUT)
GPIO.setup(GREEN, GPIO.OUT)

GPIO.output(RED, GPIO.HIGH)
GPIO.output(GREEN, GPIO.HIGH)

time.sleep(2) # この間は点灯し続ける

GPIO.cleanup() # <- 消灯

GPIO.setmode(GPIO.BCM) # GPIO番号で指定
GPIO.setup(RED, GPIO.OUT) # GPIOを出力として使用する
GPIO.setup(GREEN, GPIO.OUT)

GPIO.output(RED, GPIO.LOW) # GPIO 25の出力をLOWに設定する（LOWにすることでLEDが消灯）
GPIO.output(GREEN, GPIO.LOW)

GPIO.cleanup() # <- 消灯
