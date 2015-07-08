#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

my @A = qw(J A C K);
my @B = qw(D A N I E L);

my $str = undef;

while ( (scalar(@A) > 0) || (scalar(@B) > 0) ) {
    if ( @A && @B ) {
        if ( ord($A[0]) < ord($B[0]) ) {   
            my $aa = shift(@A);
            $str = $str.$aa; 
        } else {
            my $bb = shift(@B);
            $str = $str.$bb;
        }
    }elsif ( @A) {
        my $aa = shift(@A);
        $str = $str.$aa; 

    }elsif ( @B ) {
        my $bb = shift(@B);
        $str = $str.$bb;
    }
}
say "Lexical string is $str";

