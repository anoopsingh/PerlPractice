#!/usr/bin/python


dict = {'name':'anoop', 'age':29, 'class1':'btech'}


print "dict['name']", dict['name']
print "dict[age]", dict['age']
print "dict[class]", dict['class1']


""" Updating dictionary """

dict['name'] = 'advay'
dict['age'] = '05 months'

print dict['name']
print dict['age']


""" deleting element from dict """
del dict['name']
print dict
dict.clear()
print "-----------------------------------"
print dict
print "-----------------------------------"
del dict
print dict

