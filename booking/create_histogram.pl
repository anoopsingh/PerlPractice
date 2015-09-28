#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


open(FH,"<$ARGV[0]") or die "No such file $! \n";
my $max = 0;
my %hash_word ;
while(<FH>) {
    chomp $_;
    foreach ( split ("",$_) ) {
        my $char = $_; 
        if ( exists $hash_word{$char} ) {
            $hash_word{$char}  = ++$hash_word{$char};
        } else {
            $hash_word{$char} = 1;
        }
        if ( $hash_word{$char} > $max ) {
            $max = $hash_word{$char};
        }
    }
}

say "$max -----------------------";
say Dumper \%hash_word;
#### draw histogram

foreach my $i( sort {$a cmp $b} keys %hash_word) {

    say "*" x int(($hash_word{$i} * 80) / $max),$i;

};

foreach my $i( reverse sort {$hash_word{$a} <=> $hash_word{$b}} keys %hash_word) {

    say "*" x int(($hash_word{$i} * 80) / $max),$i;

};



