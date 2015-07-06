# filter map reduce

def f(x):

    return x % 3 == 0  or x % 5 == 0

aa = filter(f,range(2,25))

print aa


def cubes(x):

    return x*x*x


ss = map(cubes, range(3,100))

print ss
