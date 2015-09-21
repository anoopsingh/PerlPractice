#!/use/bin/perl -w
use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

#Input: two arrays of integers
my $hash = {};

my $array1 = [ 1, 7, 8, 2, 4, 5 ];
my $array2 = [ 3, 5, 1, 7, 6, 9 ];



foreach my $item ( @$array1 ) {

    if ( ! exists $hash->{$item} ) {
        $hash->{$item} = 1;
    }

}
foreach my $item ( @$array2 ) {

    if (  exists $hash->{$item} ) {
        $hash->{$item} = ++$hash->{$item};
    } else {
        $hash->{$item} = 1;
    }

}
my @common = sort (grep { $hash->{$_} >= 2} keys %$hash);
say "Smallest common integer is $common[0]";

