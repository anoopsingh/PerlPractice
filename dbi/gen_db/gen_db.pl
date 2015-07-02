#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use DBI;
use Data::Dumper;


my $db_name = 'genSmart';
my $host = 'localhost';
my $user = 'root';
my $password = '';
my $port = 3306;
my $dsn = "DBI:mysql:database=$db_name;host=$host:port=$port";

my $dbh;
eval {
$dbh = DBI->connect($dsn, $user, $password, {raiseError =>1, autoCommit=>1}) or die "Can not connect to database $DBI::errstr\n";
};

if ( $@ ) {
    print "$@ occured"
}

my $query = "select * from users";

my $sth = $dbh->prepare($query);

$sth->execute();

while ( my $rows = $sth->fetchrow_arrayref) {

    say "------------- @$rows";

}

