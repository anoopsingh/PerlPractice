#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;

#my $input_str = "forgeeksskeegfor";
my $input_str = $ARGV[0];
longestPalSubstr($input_str);

sub longestPalSubstr {
   
    my $str = shift;
    my $maxLength = 1;
    my $start = 0;
    my $len = length($str);
    my $high;
    my $low;
    for ( my $i=1; $i < $len; ++$i) {
        $low = $i -1;
        $high = $i;
        # for even string
        while ( $low >=0 && $high < $len && ( substr($str,$low,1)  eq substr($str,$high,1)) ) {
            if ( ($high - $low + 1) > $maxLength){
                $start = $low;
                $maxLength = ($high - $low +1 );
            }
            $low--;
            $high++;
        }
        $low = $i - 1;
        $high = $i + 1;
        #for odd string
        while ( $low >=0 && $high < $len && ( substr($str,$low,1)  eq substr($str,$high,1)) ) {
            if ( ($high - $low + 1) > $maxLength) {
                $start = $low;
                $maxLength = ( $high - $low + 1);
            }  
            $low--;
            $high++;
        }

    }
    say "Longest palidrom sunstring is :";
    my $palin = substr($str, $start, $maxLength);
    say $palin;
}
