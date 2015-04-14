#!/usr/bin/perl -w
use strict;
#use warnings;
use Data::Dumper;
use 5.10.0;

### Anonymous hash example

my $hash = {anoop=>'singh', pinku=>'king', key=>'value'};
my $array = [12 , 42 , 42353, 35];

foreach (@$array) {
    say $_;
}
say Dumper $hash;
foreach my $key ( keys %{$hash} ) {
    say "key is $key ";
    say "value is $hash->{$key}";
}


