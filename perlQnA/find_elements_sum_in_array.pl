#!/usr/bin/perl

use strict;
use warnings;
use Data::Dumper;


my $num = $ARGV[0];

my @array = qw(12 43 23 87 32 28 78 33 );
print Dumper \@array;

my %hash = ();


for (my $i=0;$i<=$#array;$i++) {

    if ( !exists $hash{$array[$i]} ) {
        $hash{$array[$i]} = $i;
    }

}

print Dumper \%hash;

for (my $i=0;$i<=$#array;$i++) {

    my $comp = $num - $array[$i];

    if ( exists $hash{$comp} ) {

        print "sum found at index $i \t $hash{$comp}\n";
    }

}
