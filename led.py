#!/usr/bin/python
# coding=utf-8 
from Adafruit_PWM_Servo_Driver  import PWM
import RPi.GPIO as GPIO
import time
import sys
import sysv_ipc


# Initialise the PWM device using the default address
# bmp = PWM(0x40, debug=True)
pwm = PWM(0x40, debug=True)

# memoriacimek definialasa
red_address = 0x78
green_address = 0x79
blue_address = 0x80
sw_address = 0x81
canvas_up_address = 0x82
canvas_down_address = 0x83

servoMin = 0  # Min pulse length out of 4096
servoMax = 4095  # Max pulse length out of 4096
 
def setServoPulse(channel, pulse):
  pulseLength = 1        # 1,000,000 us per second
  pulseLength /= 1000                     # /= VALUE Hz
  print "%d us per period" % pulseLength
  pulseLength /= 4096                     # 12 bits of resolution
  print "%d us per bit" % pulseLength
  pulse *= 1
  pulse = pulseLength
  pwm.setPWM(channel, 0, pulse)

pwm.setPWMFreq(1000)                     # Set frequency to (VALUE) Hz

# memoriacimek nyitasa
r = sysv_ipc.SharedMemory(red_address)
g = sysv_ipc.SharedMemory(green_address)
b = sysv_ipc.SharedMemory(blue_address)
s = sysv_ipc.SharedMemory(sw_address)
cu = sysv_ipc.SharedMemory(canvas_up_address)
cd = sysv_ipc.SharedMemory(canvas_down_address)

red = 0
green = 0
blue = 0
kapcs = 99
canvas_up = 99
canvas_down = 99


while True:
    
	# piros memoria olvasasa
	red_value = r.read()
	# hanyadik karakter a lezaro
	i = red_value.find('r')
	# lezaro karakter levagasa
        if i != -1:
		red_value = red_value[:i]
        
	# zold memoria olvasasa
	green_value = g.read()
        # hanyadik karakter a lezaro
        i = green_value.find('g')
	# lezaro karakter levagasa
        if i != -1:
                green_value = green_value[:i]

	# kek memoria olvasasa
	blue_value = b.read()
        # hanyadik karakter a lezaro
        i = blue_value.find('b')
	# lezaro karakter levagasa
        if i != -1:
        	blue_value = blue_value[:i]

	#kapcsolo memoria olvasasa
	kapcs_value = s.read()
	#hanyadik karakter a lezaro
	i = kapcs_value.find('s')
	#lezaro karakter levagasa
	if i != -1:
		kapcs_value = kapcs_value[:i]
		
	
	#canvas_up memoria olvasasa
	canv_up_value = cu.read()
	#hanyadik karakter a lezaro
	i = canv_up_value.find('cu')
	#lezaro karakter levagasa
	if i != -1:
		canv_up_value = canv_up_value[:i]
	#canvas_down memoria olvasasa
	canv_down_value = cd.read()
	#hanyadik karakter a lezaro
	i = canv_down_value.find('cd')
	#lezaro karakter levagasa
	if i != -1:
		canv_down_value = canv_down_value[:i]
			
        if kapcs_value.strip('\0') == 'true':
           pwm.setPWM(4,0,4095)
        if kapcs_value.strip('\0') == 'fal':
           pwm.setPWM(4,0,0)
		   
	if  canv_up_value.strip('\0') == 'onvff':
           pwm.setPWM(5,0,4095)
	if  canv_up_value.strip('\0') == 'offvf':
           pwm.setPWM(5,0,0)
		
	if  canv_down_value.strip('\0')	== 'onvll':
           pwm.setPWM(6,0,4095)
	if  canv_down_value.strip('\0') == 'offvl':
           pwm.setPWM(6,0,0)



        if red != red_value:
            red = red_value
            pwm.setPWM(0, 0, int(red.strip('\0')))
        if green != green_value:
            green = green_value
            pwm.setPWM(1, 0, int(green.strip('\0')))
        if blue != blue_value:
            blue = blue_value
            pwm.setPWM(2, 0, int(blue.strip('\0')))
        if int(red_value.strip('\0')) == 0 and int(green_value.strip('\0')) == 0 and int(blue_value.strip('\0')) == 0:
            pwm.setPWM(3, 0, 0)
        else:
            pwm.setPWM(3, 0, 4095)
