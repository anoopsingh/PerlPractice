#!/usr/bin/perl -w
use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

my $scalar = 'This is a scalar vriable';
my @array = ('anoop', 'singh', 123, 432);
my %hash = (anoop=>'singh', key=>'value', fruit=>'babana');
## create the refrecnes

my $scalar_r = \$scalar;
my $array_r = \@array;
my $hash_r = \%hash;

# Derefrecning the refs

say "$$scalar_r";
say "$array_r->[0]";
say "$array_r->[1]";
say "$array_r->[2]";
say "$array_r->[3]";
say "$hash_r->{anoop}";
say "$hash_r->{key}";
say "$hash_r->{fruit}";
$hash_r->{fruit} = 'apple';
say $hash{fruit};
$array_r->[0] = 'changed';

say $array[0];
