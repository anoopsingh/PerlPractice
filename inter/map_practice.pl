#!/usr/bin/perl

use strict;
use warnings;
use feature qw/say/;
use Data::Dumper;

my @numbers = qw(3 4 6 3 56);
my @squares = map { $_ * $_} @numbers;
my @list = map { $_ > 5 ? ($_ * $_) : ()} @numbers;

## other way of doing so 
my @list1 = map { $_ * $_} grep { $_ > 5} @numbers;
say Dumper \@numbers;
say Dumper \@squares;
say Dumper \@list;
say Dumper \@list1;


## map always returns a key

my %hash = map { $_ => 1} @numbers;
my @uni = keys %{{map { $_ => 1} @numbers}};

say Dumper \%hash;
say Dumper \@uni;
my $ip = $ARGV[0];

if ( $ip =~ /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/) {
     if ( ($1 <=255) && ($2 <=255) && ($3 <=255) && ($4 <=255)){
        say "valid ip is $ip";
     } else {
        say "not a valid ip";
     }

}
