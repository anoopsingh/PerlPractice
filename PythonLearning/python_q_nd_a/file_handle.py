#!/usr/bin/python

import os
name = raw_input("Enter you name")
print "------------------------------"
print name
print "------------------------------"

title = raw_input("Enter your title")

print "------------------------------"
print title 
print "------------------------------"


# open a file

fo = open('prime.py',"r")

print "File name is", fo.name
print "Access mode is", fo.mode
print "Is file closed", fo.closed

### write file

wr = open("test",'w')
wr.write('helolo m gud guy all the pain u get insiise u might not hsow shale on me blame on meu could d blamne on me')
wr.close()

rd = open("test", "r+")

str = rd.read(20)
print "str at 10 is", str

#check current position

pos = rd.tell()
print "pos is", pos
rd.seek(0,0)
str = rd.seek(10)
print "not ointer is at :", str


os.rename("test","test1")
os.remove("test1")
os.mkdir("log1")
os.rmdir("log")
os.getcwd()
