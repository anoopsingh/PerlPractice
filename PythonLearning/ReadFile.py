#!/usr/bin/python
import os

fo = open('class.py','rw+')

print "Name of the file", fo.name



line = fo.readlines()
print "Read line: %s" %(line)

line = fo.readline(2)
print "Read line: %s" %line
