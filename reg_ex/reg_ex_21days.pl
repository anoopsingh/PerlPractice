#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;

use Data::Dumper;


################# Regular expression concptes from ref 21 days ##################
##### Case 1, basic pattern matching

my $pattern = 'anoop kumar singh';

my $match = $pattern =~/kumar/;

say "$match found in $pattern";


say "Aslk me a question politely ?:";

my $question = <stdin>;
chomp $question;


if ( $question =~ /please/i ) {
    say "U are very polite";
} else {
    say "U doint have any politeness";
}


##### Case 2



