#!/usr/bin/perl

use DBI;
use strict;
use Data::Dumper;

my $driver = "mysql"; 
my $database = "genSmart";
my $dsn = "DBI:$driver:database=$database";
my $user = "root";
my $password = "";
my $dbh = DBI->connect($dsn, $user, $password ,{ RaiseError => 1, AutoCommit => 0 });

my $statement = "select * from regressionsuite where regressionsuite_name = ?";
#my $hash_ref;
#eval {
#    $hash_ref = $dbh->selectrow_hashref("select * from regressionsuite");
#};
#
#if ($@) {
#    print $@;
#}
#

my $sth = $dbh->prepare("$statement");
$sth->execute('testing');
while ( my @row = $sth->fetchrow_array ) {
    print "@row\n";
}

$dbh->commit();
$dbh->disconnect();
