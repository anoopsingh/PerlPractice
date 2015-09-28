#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


#Interview Questions
#Convert number above in hexadecimal
#312312
#Ex.
#255 -> FF
#254 -> 254 div 16 = 14, int(254/16) = 15. -> F, E
##Remainder source base algorithm

my $number = $ARGV[0];
my $hex = toHex($number);
say "Hex equivalent of $number is $hex";
sub toHex {

    my $num = shift;
    my $result;
    my $r = ($num % 16);
    if ( ($num-$r) == 0 ) {
        $result =toChar($r);
    } else {
        $result = toHex(($num - $r)/16).toChar($r);
    }
    return $result;
}


sub toChar {

    my $pos = shift;
    my $str = '0123456789ABCDEF';
    return substr($str,$pos,1);

}
