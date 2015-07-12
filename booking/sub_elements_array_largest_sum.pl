#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;

#my @array  = qw(0 1000 3453 12 33 -32 433 34 -23 433 443 500 -30 54);
my @array = qw(90 -1 4 -6 3 -2 8 0 9 -1 10 -1 60 -2 40);
my $add = 0;
my $prev= 0;
for ( my $ii = 0; $ii<=$#array; $ii++ ) {
    $add = $add + $array[$ii];
    if ( $add > $prev ) {
        $prev = $add;
        $add = 0;
    }
}

say "Largest contigeous sum is $prev";

