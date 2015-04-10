#!/usr/bin/perl -w

use strict;
use warnings;
use feature qw/say/;

my $mainstring="APerlAReplAFunction";
my $count = ($mainstring =~ tr/A//);
$mainstring =~ tr/A//;
say "There are $count As in the given string";
say $mainstring;

my @array1 = ("tea","coffee","tea","cola","coffee");
say join(" ", @array1);
say join(" ", uniqueentr(@array1));
sub uniqueentr {
    return keys %{{ map { $_ => 1 } @_ }};
}


