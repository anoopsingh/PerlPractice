#!/usr/bin/python

""" This program will explain about the data and strign types in python """

print "hello ! Anoop"


print 2 + 2
print 5 / 2
print 50 - 5*6
print (50 - 5*2)/2
print 5 ** 2
print 2 ** 7
width = 10
length = 5
Area = (width * length)

print Area

""" string type """

print """\
    Usage[things]: Options
    -h Display all the options
    H hostname
    """

str = 3 * 'un' + 'aks'
print str


aa = 'py'   'thon'
print aa


text = ('put several lines together'
         ' to have them joined together')

print text


word = 'python'

print word[0]
print word[5]


print word[-6]
print word[-1]

print word[0:3]
print word[3:5]

print word[:2] + word[2:]

print word[:4] + word[4:]


print word[2:45]
print 'j' + word[1:]

str = 'adadqadadada33rwderrww3rsfefsfadq3rfwfwrwfwrwf'
print len(str)


print u'hello Anoop'
aa = u'hello\u0020Anoop'

print aa


""" getting started with List """

list1 = [2, 44, 54, 76, 23, 34]
print list1


""" using : one create the shallow copy """

print list1[:]


""" list supports operation like concatenation """

print list1 + [23, 343, 533]

print list1


cubes = [1, 8, 27, 65, 125]
cubes[3] = 64

print cubes
