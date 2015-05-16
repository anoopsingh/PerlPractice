#!/usr/bin/perl -w

use strict;
use warnings;
use DBI;
use Data::Dumper;

open(FH,"<vsbc_supported") or die "Can't open no such file or directory $!";
#open(FH1,"<total_suites_db")or die "Can't open no such file or directory $!";
open(FH1,"<smoke_list_db")or die "Can't open no such file or directory $!";
#open(WR,">vsbc_list")or die "Can't open no such file or directory $!";
open(WR,">vsbc_list_smoke")or die "Can't open no such file or directory $!";

my @tot_suites = ();
my @vsbc_list_provided = ();

while (<FH1>) {
    chomp $_;
    push(@tot_suites, $_);
}

while (<FH>) {
    chomp $_;
    push(@vsbc_list_provided, $_);
}


foreach ( @tot_suites ) {
    my $ii = $_;
    foreach ( @vsbc_list_provided ) {
        if ( $ii =~ /^$_\./ ) {
            print WR "$ii\n";
        }elsif ( $ii =~/^$_$/) {
            print WR "$ii\n";
        }
    }

}

close(FH);
close(FH1);
close(WR);
