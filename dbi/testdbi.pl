#!/usr/bin/perl

use DBI;
use strict;
use Data::Dumper;

my $driver = "mysql"; 
my $database = "genSmart";
my $dsn = "DBI:$driver:database=$database";
my $userid = "root";
my $password = "";
my $dbh = DBI->connect($dsn, $userid, $password ,{ RaiseError => 1, AutoCommit => 0 });

my $statement = "select * from users where userName = ?";
my $hash_ref;
eval {
    $hash_ref = $dbh->selectrow_hashref("select * from users");
};

if ($@) {
    print $@;
}
print "------------------------------------------\n";
print Dumper $hash_ref;
print "userName is $hash_ref->{userName}\n";
print "------------------------------------------\n";
my $emps = $dbh->selectall_arrayref(
    "select * from users",
    { Slice => {} }
);
print Dumper $emps;
foreach my $emp ( @$emps ) {
    print "Employee: $emp->{userName}\n";
}

print Dumper $hash_ref;
print "============================================\n";

my $sth = $dbh->prepare("$statement");
$sth->execute('nitin');
while ( my @row = $sth->fetchrow_array ) {
    print "@row\n";
}

$dbh->commit();
$dbh->disconnect();
