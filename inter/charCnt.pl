#!/usr/bin/perl

use strict;
use warnings;


my $str = 'AAAAABBBBBBBAAAFAAA';


my $cnt = ( $str =~tr/A//);

print "The total count is $cnt\n";
print "the main str is $str\n";
