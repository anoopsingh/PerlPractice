#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

my $num_of_tests = <>;
chomp $num_of_tests;
my @str_input_list;

foreach my $i ( 1 .. $num_of_tests) {
    my $str1 = <>;
    chomp $str1;
    push(@str_input_list, $str1);
}

foreach my $ii ( 1 .. $num_of_tests ) {
    my $s1 = shift(@str_input_list);
    anagramCalculator($s1);
}

#open(FH,"<aa");
#while(<FH>) {
#    chomp $_;
#    anagramCalculator($_);
#}
sub anagramCalculator {

    my $str = shift;
    my $length = length($str);
    #say "length is $length";
    my $char_suffling = 0;
    my (%hash_s1,%hash_s2);
    if ( $length % 2 == 0 ) {
        my $s1 = substr($str,0,$length/2);
        my $s2 = substr($str,$length/2);
        my @s1_list = sort split('',$s1);
        my @s2_list = sort split('',$s2);
        foreach my $val ( @s1_list ) {
            if ( exists $hash_s1{$val} ) {
                $hash_s1{$val} = $hash_s1{$val} + 1;
            } else {
                $hash_s1{$val} = 1;
            }
        }
        foreach my $val ( @s2_list ) {
            if ( exists $hash_s2{$val} ) {
                $hash_s2{$val} = $hash_s2{$val} + 1;
            } else {
                $hash_s2{$val} = 1;
            }
        }
        foreach my $val (keys %hash_s1 ) {
            if ( exists $hash_s2{$val} ) {
                $hash_s2{$val} = ($hash_s2{$val} - $hash_s1{$val} )
            }
        }
        my @val1 = values(%hash_s2);
        #say Dumper\@val1;
        foreach my $m( @val1 ) {
            if ( $m > 0 ) {
                $char_suffling = $char_suffling + $m;
            }
        }
    } else {
        $char_suffling = -1;
    }
    #say Dumper \%hash_s1;
    #say Dumper \%hash_s2;
    say "$char_suffling";
}
