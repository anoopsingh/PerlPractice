#! /usr/bin/perl

use Person;
use strict;
use warnings;
use Data::Dumper;

my $obj = new Person("Anoop", "Singh" , "304623");

my $ret = $obj->getFirstName();
print "First name is $ret\n";

my $set = $obj->setFirstName("Dipika");
$ret = $obj->getFirstName();
print "First name is $ret\n";
