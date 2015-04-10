#!/usr/bin/perl -w

use strict;
use warnings;

package Foo;

sub foo {
    print "Hello $_[0]\n";
}

sub bar {
    print "World $_[0]\n";
}

1;
