#!/usr/bin/perl

use Employee;


my $obj = new Employee('Amit', 'Kumar', 'w55554w');

my $firstName = $obj->getFirstName();
print "FirstName befoire setting the values $firstName \n";

$obj->setFirstName('Chintu');


my $firstName = $obj->getFirstName();
print "FirstName after setting the values $firstName \n";


$obj->setLastName('Singh');
my $lastName = $obj->getLastName();
print "LastName after setting the values $lastName \n";
