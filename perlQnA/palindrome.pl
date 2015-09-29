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
    my $len = length($str);
    my ($low,$high);
    my $start = 0;
    for ( my $i = 1; $i<$len; $i++) {

        #For even palindrome string
        $low = $i -1;
        $high = $i;
        while ( ($low > 0 && $high < $len) && (substr($str,$low,1) eq substr($str,$high,1))){
            if ( $high - $low > $maxLength ) {
                $start = $low;
                $maxLength = ( $high - $low + 1);
            }
            $low--;
            $high++;
        }
        # For odd length palindrome
        $low = $i -1;
        $high = $i +1;
        while( $low >0 && $high < $len && (substr($str,$low,1) eq substr($str,$high,1) )) {
            if($high-$low+1>$maxLength) {
                $start = $low;
                $maxLength = $high - $low + 1;
            }
            $high++;
            $low--;
        }
   }
    say "Longest palidrom sunstring is :";
    my $palin = substr($str, $start, $maxLength);
    say $palin;
}
