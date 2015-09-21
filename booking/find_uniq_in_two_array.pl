#!/use/bin/perl -w
use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

#Input: two arrays of integers
#Output: one array of integers
#        which occur in only one (not both) arrays
#
#Test case:
#Input: [ 1, 7, 8, 2, 4, 5 ]
#        [ 3, 5, 1, 7, 6, 9 ]
#Output: [ 8, 2, 4, 3, 6, 9 ]  


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
        $hash->{$item} = 2;
    } else {
        $hash->{$item} = 1;
    }

}

#say Dumper $hash;

my @uniq = grep { $hash->{$_} == 1} keys %$hash;

say Dumper \@uniq;


