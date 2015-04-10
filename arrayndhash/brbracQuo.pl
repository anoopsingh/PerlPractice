#! /usr/bin/perl

use strict;
use warnings;

use Tie::RefHash;
my $push = "pop on";
print "$push\n";
print "${push}over\n";

my $concat = "${push}over";

print "\n ======> $concat \n";


## ref of hash keys

tie my %h, 'Tie::RefHash';
%h = (
    ["this", "here"]   => "at home",
    ["that", "there"]  => "elsewhere",
);
while ( my($keyref, $value) = each %h ) {
    print "@$keyref is $value\n";
}
