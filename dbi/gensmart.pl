#update regressionsuite set regressionsuite_duttypes=concat(regressionsuite_duttypes, ',G9') where regressionsuite_name='anoop_test';


use DBI;
use Data::Dumper;

open(FH,"<aa") or die "Can't open no such file or directory $!";
open(FH1,"<not_dsbc.txt")or die "Can't open no such file or directory $!";

while (<FH>) {
    chomp $_;
    push(@tot_suites, $_);
}

while (<FH1>) {
    chomp $_;
    push(@not_dsbc, $_);
}

my %not_dsbc_hash;
my %tot_suite_hash;

%not_dsbc_hash = map { $_ => 1} @not_dsbc;
%tot_suite_hash = map {$_ => 1} @tot_suites;

#print Dumper \%not_dsbc_hash;
#print Dumper \%tot_suite_hash;

foreach ( @tot_suites ) {
    my $ii = $_;
    my $flag = 1;
    foreach ( @not_dsbc ) {
        if ( $ii =~ /^$_\./ ) {
            $flag = 0;
            #print "=====================> $_ \n";
        }elsif ( $ii =~/^$_$/) {
             $flag = 0;
             #print "---------------------->$ii\n"
        }
    }
    if ( $flag ) {
        push ( @dsbc_supported, $ii );
    }

}

print Dumper \@dsbc_supported;
