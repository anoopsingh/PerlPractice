class Complex:

    """ This is a sample python class """
    def __init__(self, realpart, imagpart):
        self.r =  realpart
        self.i = imagpart

class MyClass:

    """ A simple example class """
    i = 12345
    def f(self):
        return "Hello in my Class"


x = Complex(3.0, -4.5)

print x.r
print x.i
xx = MyClass()
aa = MyClass.i
bb = xx.f()

print aa
print bb


class Complex:

    def __init__(self, real,inte):
        self.r = real
        self.i = inte


yy = Complex(3.0, -4.5)
print yy.r
print yy.i






############### Class and instance variables ###################

class Dog:

    kind = 'canine'

    def __init__(self, name):
        self.n = name


d = Dog('Tom')
d.kind = 'Anoop'
e = Dog('Tomaaaaaaaaa')
print d.n
print d.kind
print e.n
print e.kind


class Ram:

    trics = []

    def __init__(self,name):
        self.name = name

    def add_tric(self,tric):
        self.trics.append(tric)

d = Ram('Fido')
e = Ram('Buddy')

d.add_tric('role over')
e.add_tric('play dead')

print d.trics
