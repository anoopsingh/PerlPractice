#!/usr/bin/perl


print range(10)
print range(5,10)
print range(0,10,3)
print range(-10,-100,-30)


a = ['anoop' , 'kumar', 'singh', 'dipika', 'ritu', 'adyav']

for i in range(len(a)):
    print i, a[i], " , length = ", len(a[i])
