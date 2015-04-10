#!/usr/bin/perl

print "Hello";


## creating hard refrences

my $foo = 'hello';

my $fooref = \$foo;

## create has refrences to a subroutine

sub foo { print "foo \n"; }

$foosub = \&foo;

## anonymous refrence arrays

$array = ['www', 'dddd', 'fffff' ];
