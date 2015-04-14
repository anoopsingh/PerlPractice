#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

my $hr;

$hr->{scalar} = 'a string';
$hr->{array} = [12, 33,334];
$hr->{hash} = {anoop=>'singh', ssss=>'ees'};

my %hash_copy = %{$hr->{hash}};
my @array_copy = @{$hr->{array}};

$array_copy[0] = 'this one is modified';
say "============ $hr->{array}->[0]";






