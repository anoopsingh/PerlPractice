#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;

use Data::Dumper;

sub foo {

    #return (wantarray()? qw(a, b, c): '1');
    return ( qw(a, b, c));

}

my $result = foo();
my @result = foo();


say "Foo in scalar context $result";
say "Foo in array context @result";
