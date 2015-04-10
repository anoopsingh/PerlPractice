#! /usr/bin/perl;

use Person;

my $personObj = new Person('Anoop', 'Singh', 'dbaps1271r');

my $firstName = $personObj->getFirstName();

print "First Name before setting  is $firstName\n";

$personObj->setFirstName('Pinku');


my $firstName = $personObj->getFirstName();


print "First Name after setting is $firstName\n";
