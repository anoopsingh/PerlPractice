#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


sub counter_maker {

    my $start = 0;
    return sub {
        return $start++;
    };
}

my $counter = counter_maker();
for(my $i=0; $i<5;$i++) {
    say &$counter;
}
