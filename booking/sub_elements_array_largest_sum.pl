#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
my @arr = qw(90 -1 4 -6 3 -2 8 0 9 -1 -31 -1 60 -2 40);
#my @arr = (1, 2, -3, 3, -7, 5, 4, -1, 4, 5);
my $add = $arr[0];
my $prev= $arr[0];

for ( my $ii = 1; $ii<=$#arr; $ii++ ) {
    $add = $arr[$ii] + $add;
    $add = $add > $arr[$ii] ? $add : $arr[$ii];
    $prev = $prev > $add ? $prev : $add;
}

say "Largest contigeous sum is $prev";

