#!/usr/bin/python -tt

import sys
import os

def List(dir):
    filenames = os.listdir(dir)
    print filenames
    for filename in filenames:
        path = os.path.join(dir, filename)
        print path
        print os.path.abspath(path)

def main():
    List(sys.argv[1])

if __name__ == '__main__':
    main()
