#!/usr/bin/perl -w

use strict;
use warnings;


use Data::Dumper;
use 5.10.0;

my @A = ( 1, 7, 8, 2, 4, 5, 10, 10 );
my @B = ( 3, 5, 1, 7, 6, 9 );

my $hash = {};

foreach (@A) {
    
    $hash->{$_} = 1;
}

#say Dumper $hash;


foreach ( @B) {
    $hash->{$_} = $hash->{$_} ? 2:1;
}
#say Dumper $hash;
my @C;
my @D;

for ( keys %$hash ) {

   push @C, $_ if $hash->{$_} == 1;
   push @D, $_ if $hash->{$_} == 2;

} 

say Dumper \@C;
say Dumper \@D;










