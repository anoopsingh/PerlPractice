#!/usr/bin/perl

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


## Changing and adding elements

my @browser = qw(NS IE OPERA);
say Dumper \@browser;
$browser[3] = 'Mosaic';
say Dumper \@browser;
$browser[2] = 'Mosaic';
say Dumper \@browser;


##### shift and unshift

unshift(@browser,"Anoop Singh");
say Dumper \@browser;
my $item = shift(@browser);
say Dumper \@browser;
say $item;


###### push and pop ######

push(@browser, 'Dipika');
say Dumper \@browser;
my $last = pop(@browser);
say Dumper \@browser;
say $last;
#######################################################

######### chop and chomp ##########

chop(@browser);
say Dumper \@browser;
chomp(@browser);
say Dumper \@browser;

################################ Slice to slice and dice arryas in perl

## how to remove eleme t from middle of an arrya
my @dwarfs =qw( anoop kumar singh ritu dipika amit soni moni );
splice @dwarfs,3,2;
say @dwarfs;

################################################## inseert elemnt in the middle of a array
splice @dwarfs, 2,2,'dipika';
say @dwarfs;

####################### insertt list of elements

splice @dwarfs,3,0,'Lion','Dog';
say @dwarfs;
my @dwarfs = qw(Doc Grumpy Happy Sleepy Sneezy Dopey Bashful);
my @who = splice @dwarfs, 3, -1;
say "@who";
say @dwarfs;

my @dwarfs = qw(Doc Grumpy Happy Sleepy Sneezy Dopey Bashful);
my @who = splice @dwarfs, -3, -1;
say "@who";
say @dwarfs; 
