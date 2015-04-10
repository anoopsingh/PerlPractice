#!/usr/bin/perl

use strict;
use warnings;

use 5.10.0;


my @browser = qw/NS IE Opera/;

$browser[3]= 'mozilla';

say @browser;

#splice(@browser,0,2);
say @browser;
splice(@browser,0,2,'hum','anoop');
say @browser;

## addd element in begining

unshift(@browser,'rahul');
say @browser;
my $delete = shift(@browser);
say $delete;
say @browser;
