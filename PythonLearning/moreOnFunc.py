#!/usr/bin/python

""" default argument fucntion """

def ask_ok(prompt, retires=4, complaint='yes or no, please !'):
    while True:
       ok = raw_input(prompt)
       if ok in ('y', 'ye', 'yes'):
           return True
       if ok in ('n', 'no', 'nopes'):
           return False
       retries = retries - 1
       if retries < 0:
           raise IOError('refusink user')
       print complaint

ask_ok('Do you really want to quit ?')
ask_ok('ok to overrite the file ', 2)
ask_ok('ok to overrite the file ', 2, 'common yes or no')
