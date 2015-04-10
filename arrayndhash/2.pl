#! /usr/bin/perl

use strict;
use warnings;
use Tie::RefHash;

my $push = 'pop on';
print "${push}over\n";

my $concat = "${push}over";

print "\n=========== $concat \n";

tie my %h, 'Tie::RefHash';

%h = ( ["this", "that"]=> "at home", ["that", "there"] => "else where");

while ( my ($key, $value) = each %h ) {
      print "@$key is $value\n";
}
