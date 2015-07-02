#!/usr/bin/perl -w
use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


my @AOA = (['hello','adaa'],[424, 424, 524],['hum', 'tum', 'ek']);

foreach my $i(@AOA) {
    say "@$i";
}

my @arr1 = qw(da adf dsad ffsf fsdfs adsesss Anoop sfsgs sfsfds sfs);

my @sorted_array = sort{$a cmp $b}@arr1;
#case sensitive sorting

@sorted_array = sort{uc $a cmp uc $b}@arr1;
foreach ( @sorted_array ){

    say $_;
}

my @num = qw(12 323 43 4 4 24 4 43 43 545);
@num = sort {$a <=> $b} @num;
say @num;
my %hash = ("rahul"=>23, "anoop"=>34, "dipika"=>28);
foreach (sort keys %hash){
    say "key is $_ value is $hash{$_}";
}

foreach (sort {$hash{$a} <=> $hash{$b}} keys %hash){
    say "kesy is $_ value is $hash{$_}";
}




















