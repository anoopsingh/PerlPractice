#!/usr/bin/perl

use strict;
use warnings;

my @array = qw(12 23 34 56 78 87 90 99  123);
my $found = 1;

my $mid_point =1;
my $item = $ARGV[0];
$mid_point = scalar(@array)%2 == 0? scalar(@array)/2: (scalar(@array)+1)/2;

$mid_point = $mid_point - 1;

print "\n \t \t mid point is \t $mid_point \n";

while( $found ) {

    if ( $array[$mid_point] == $item ) {
         $found = 0;
         print "item \t $item  \t found at index \t $mid_point \n";
    } elsif ( $array[$mid_point] > $item ) {
         @array = splice ( @array,0,$mid_point-1);
         print " item less than val at mid @array\n";

    } elsif ( $array[$mid_point] < $item ) {
         @array = splice ( @array,$mid_point+1);
         print " item greater than val at mid @array\n";
    }
    $mid_point = scalar(@array)%2 == 0? scalar(@array)/2: (scalar(@array)+1)/2;
    $mid_point = $mid_point - 1;
    print "mid point is $mid_point and val is @array\n";
    
}
