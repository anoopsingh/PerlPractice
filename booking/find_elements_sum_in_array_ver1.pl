#!/usr/bin/perl

use strict;
use warnings;
use Data::Dumper;


my $num = $ARGV[0];

my @array = qw(12 43 23 87 32 28 78 33 );

@array = reverse sort (@array);

my $l = 0;
my $r = $#array;
my $flag = 1;
while ( $l < $r ) {

    my $tmp = $array[$l] + $array[$r];
    if ( $tmp == $num ) {
        print "\t $array[$r]  $array[$l]\n" ;
        exit;
    } elsif ( $tmp > $num ) {
        $l++;
    } else {
        $r--;
    }

}
