#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


my $scalar = 'helo zadfaf f';
my @array = (12, 32, 43);
my %hash = (anoop=>'singh', kumar=>'pinku');

## assign to a anonymous hash

$arrayRef->[0]=\$scalar;
$arrayRef->[1]= \@array;
$arrayRef->[2]=\%hash;

say Dumper $arrayRef;
