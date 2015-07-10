#!/usr/bin/perl -w


use strict;
use warnings;
use 5.10.0;


my $str0 = $ARGV[0];
my $str1 = $ARGV[1];

my $str00 = join'',sort split('',$str0);
my $str11 = join'',sort split('',$str1);

if ( $str00 eq $str11 ) {
    say "strings are anagrams $str00 $str11";
}

