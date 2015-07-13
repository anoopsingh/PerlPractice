#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

my @array = qw(223 353 33 68 11 8);

say Dumper \@array;
my ($i,$j,$key);

for ($i = 1;$i<=$#array; $i++) {
    say "Iter No $i";
    $key = $array[$i];
    say "key is $key";
    $j = $i-1;
    while ( $j >= 0 && $array[$j] > $key){
        say "j is $j array[j+1] = $array[$j+1] = $array[$j] key is $key";
        $array[$j+1] = $array[$j];
        $j = $j -1;
    }
    say $j;
    $array[$j+1] = $key;
}

say Dumper \@array;
