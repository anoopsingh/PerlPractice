use DBI;
use Data::Dumper;

my $dsn = 'DBI:mysql:database=genSmart';
my $user = 'root';
my $pass = '';

my $dbh = DBI->connect($dsn,$user,$pass, {raiseError =>1, autoCommit =>1}) or die "can connect $!\n";

##update regressionsuite set regressionsuite_duttypes=concat(regressionsuite_duttypes, ',G9') where regressionsuite_name='anoop_test';

open (FH,"<dsbc_supported") or die "Can not open file $!\n";

while(<FH>) {

    chomp $_;
    my $statement = "select * from regressionsuite where regressionsuite_name = ? ";
    my $hash_ref = $dbh->selectrow_hashref("$statement", undef, $_);
    print Dumper $hash_ref;
    my $cnt = $sth->rows;
    print "num of rows selected $cnt\n";
    #print $statement . "\n";
    #$sth = $dbh->prepare("$statement");
    #$sth->execute("$_");
    #while ( my @row = $sth->fetchrow_array ) {
     #   print Dumper \@row;
    #}
}
