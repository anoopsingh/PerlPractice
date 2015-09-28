#!/usr/bin/perl -w

use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

my $input = $ARGV[0];
my $char = return_first_non_repetive_char($input);
say "First non repitive char is $char";

sub return_first_non_repetive_char {


    my $str = shift;
    my $length = length($str);
    my %word_hash;

    #hash map created    
    foreach ( split("",$str)) {
        my $char = $_;
        if ( exists $word_hash{$char} ) {
            $word_hash{$char} = ++$word_hash{$char};
        } else {
            $word_hash{$char} = 1;
        }    
    
    }#END foreach

    #scan hash for non repetive char    
    foreach my $ii(0..($length-1)) {
    
        my $ch = substr($str,$ii,1);
        if ( $word_hash{$ch} == 1 ) {
            return $ch;
            last;
        } else {
            return 0;
        }
    }
}
