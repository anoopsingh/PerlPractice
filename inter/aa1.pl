#!usr/bin/perl
use strict;
use warnings;
my $mainstring="APerlAReplAFunction";
my $count = ($mainstring =~ tr/A//);
print "There are $count As in the given string\n";
print $mainstring;
