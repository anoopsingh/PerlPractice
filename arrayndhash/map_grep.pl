#!/usr/bin/perl

use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

my @numbers = (1 .. 5);

@numbers = (1 .. 5);

say "@numbers";
my @double = map{$_ * 2} @numbers;
my @double = map { $_*2} @numbers;

say "@double";


## put array element in hash lookup

my @names = qw/anoop singh kumar rahul/;
my %hash = map { $_ => 1} @names;

my %hash = map { $_ => 1} @names;

print Dumper \%hash;
say Dumper \%hash;

