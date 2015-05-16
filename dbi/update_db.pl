#!/usr/bin/perl -w

use strict;
use warnings;
use DBI;
use Data::Dumper;

my $dsn = "DBI:mysql:database=genSmart";
my $user = "root";
my $pass = '';
my $file = $ARGV[0];

my $dbh = DBI->connect($dsn, $user, $pass, {raiseError =>1, autoCommit =>1}) or die "Can not connect to DB $DBI::errstr $!\n";

##update regressionsuite set regressionsuite_duttypes=concat(regressionsuite_duttypes, ',G9') where regressionsuite_name='anoop_test';

my $sql = "update regressionsuite set regressionsuite_duttypes=concat(regressionsuite_duttypes, ?) where regressionsuite_name= ? AND regressionsuite_type= ?";

my $sth = $dbh->prepare($sql);
open(FH,"<$file") or die "No such file or derectory $!\n";

while(<FH>) {

    chomp $_;
    my $suiteName = $_;
    print " suitename $suiteName \n";
    $sth->bind_param(1,',DSBC');
    $sth->bind_param(2,$suiteName);
    $sth->bind_param(3,'SMOKE');
    my $row = $sth->rows();
    print "$row Num of rows affected\n";
    $sth->execute();

}

close(FH);
$dbh->disconnect();
