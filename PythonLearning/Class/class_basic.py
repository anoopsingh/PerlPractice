class User:
    name = ""
    def __init__(self,name):
        self.name = name
    def sayHello(self):
        print "Hello, my name is " + self.name

# create virtual objects

James = User("James");
david = User("david");
eric = User("eric");

## Call methods own by virtual objects

James.sayHello()
david.sayHello()
