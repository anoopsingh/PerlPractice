#!/usr/bin/perl -w

use strict;
use warnings;
use Data::Dumper;
use 5.10.0;


my @array = (12, 42424, 24324, 11, 34, 87, -3, -5);
@array = sort @array;

my $len = scalar(@array);
my $min_diff = undef;

if ( $len == 2 ) {
    $min_diff = ($array[1] - $array[0]);
} elsif ( $len >2 ) {
    $min_diff = abs($array[1] - $array[0]);
    my $srt = $array[1];
    for (my $i =2; $i<=$#array ; $i++) {
        my $tempDiff = abs($array[$i] - $srt);
        say "======$array[$i] - $srt =========== $tempDiff ";
        $srt = $array[$i];
        if ( $tempDiff <= $min_diff ) {
            $min_diff = $tempDiff;
         }
    }

}

say "Min diff is $min_diff";
