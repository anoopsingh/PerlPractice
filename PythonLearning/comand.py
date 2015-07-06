#!/usr/bin/python

import sys
import os
import commands




def List(dir):
    cmd = 'ls -l ' + dir
    (status, output) = commands.getstatusoutput(cmd)
    if status:
        sys.stderr.write('There was an erro:' + output)
        sys.exitg(1)
    print status 


def main():
    List(sys.argv[1])


if __name__ == '__main__':
    main()
