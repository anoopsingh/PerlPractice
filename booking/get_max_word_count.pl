#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

my $chars = 'atuslirabocynkebbterasiuaracnbawp';
my $words = ['car','boat','plane','bus','shuttle'];

my %hash_char_count_map = create_char_hash_map($chars);

foreach ( @$words ) {

    my $count = get_char_count($_);
    say "Word $_ can be made $count times";
} 

sub create_char_hash_map {

    my $str = shift;
    my $hash = {};
    my @tmp = split('',$str);
    foreach ( @tmp ) {

        if (exists $hash->{$_} ) {
            $hash->{$_} = ($hash->{$_} + 1);
        } else {
            $hash->{$_} = 1;
        }

    }
    return %$hash;
}

sub get_char_count {

    my $wrd = shift;
    my $len = length($wrd);
    my $min_count = 1;
    foreach my $i( 0 .. ($len-1)) {
        my $word_char = substr($wrd, $i,1);
        if ( exists $hash_char_count_map{$word_char} ) {
            if ( $i == 0) {
                $min_count = $hash_char_count_map{$word_char};
            } else { 
                my $cnt = $hash_char_count_map{$word_char};
                $min_count = $cnt > $min_count ? $min_count:$cnt;
            }
        } else {
            $min_count = 0;
            last;
        } 
    }
    return $min_count;
}

