#!/usr/bin/python

def fib(n):

    result = []
    a,b = 0,1
    while a < n:
        result.append(a)
        a,b = a+b,a
    return result

ret = fib(100)
print ret
