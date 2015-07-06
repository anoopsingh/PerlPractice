#!/usr/bin/python

import re

line = "Cats are smarter than dogs"

matchObj = re.match( r'(.*) are (.*?) .*', line, re.M|re.I)

if matchObj:
   print "matchObj.group() : ", matchObj.group()
   print "matchObj.group(1) : ", matchObj.group(1)
   print "matchObj.group(2) : ", matchObj.group(2)
else:
   print "No match!!"


obj = re.match(r'dog', line, re.M|re.I)
if obj:
    print "Match found obj.group()", obj.group()
else:
    print "No match found"

obj1 = re.search(r'dog', line, re.M|re.I)
if obj1:
    print "match found obj1.group()",obj1.group()
else:
    print "match not found"



### search and replace

phone = " 9811-370-301 #this is the phone  number"

num = re.sub(r'#.*$','',phone)
print "num is ", num

aa = re.sub(r'\D','',num)
print "number only containing degits",aa
























