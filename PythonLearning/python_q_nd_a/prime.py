for n in range (2,10):
    for x in range(2,n):
        if n % x == 0:
            print "%s is composite number" %(n)
            break
    else:
        print "%s is prime number" %(n)


  

def fib(n):

    a,b = 0,1
    while a<n:
        print a
        a,b = b,a+b


fib(1000) 
