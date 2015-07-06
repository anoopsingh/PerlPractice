#!/usr/bin/python


#TODO buffering ?
f = open("write.txt",'w+')
print dir(f)
f.write('hello testing write of python')
f.write('hello testing write of python')
f.write('hello testing write of python')
f.write('hello testing write of python')

f.seek(0,0);

#f.close()
#f = open("write.txt",'r')
aa = f.read()
print "-------------------------------"
print f.read()
print "-------------------------------"
print "anoop iitt"
print aa


