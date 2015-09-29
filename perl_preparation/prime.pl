#!usr/bin/perl -w
use strict;

sub prime {
    my $number = shift;
    my $d = 2;
    my $sqrt = sqrt $number;
    while(1) {
        if ($number%$d == 0) {
            return 0;
        }
        if ($d < $sqrt) {
            $d++;
        } else {
            return 1;
        }
    }
}

my $number = $ARGV[0];
print prime($number);
