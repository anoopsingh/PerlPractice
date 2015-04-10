#!/usr/bin/perl -w

use strict;
use warnings;
use Data::Dumper;

# map for simple transformartion

my @list1 = (1 .. 5);

my @list2 = map { $_ * 2 } @list1;

print @list2;

## creating fast lookup table

my @lookup = ('Foo', 'bar','zoo','Farhan','Anoop','Amit');
print "\n";
my %lookup_hash = map { $_ => 1} @lookup;
## can have in array as well
my @lookup_array = map {$_ => 1} @lookup;

print @lookup_array;

print "\n";
print Dumper \%lookup_hash;

foreach ( keys %lookup_hash ) {
    print "key $_ value $lookup_hash{$_}\n";

}

## populate the name which starts with F

my %name_prefix = map { $_ =~ /^[F|A]/ ? ($_ => 1) : ()} @lookup;

print Dumper \%name_prefix;

my @num = qw( 4 575 424  75478 32 234 55 22);

my @gt_num = grep $_ >100, @num;
my @gt1_num = grep ($_ >100, @num);

print Dumper \@gt1_num;
print Dumper \@gt_num;
