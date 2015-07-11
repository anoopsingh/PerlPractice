#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


#https://www.hackerrank.com/challenges/morgan-and-a-string
#hhpddlnnsjfoyxpciioigvjqzfbpllssuj


#my $num_of_tests = <>;
#chomp $num_of_tests;
#my @str_input_list;
#
#foreach my $i ( 1 .. $num_of_tests) {
#    my $str1 = <>;
#    chomp $str1;
#    push(@str_input_list, $str1);
#}
#
#foreach my $ii ( 1 .. $num_of_tests ) {
#    my $s1 = shift(@str_input_list);
#    anagramCalculator($s1);
#}

open(FH,"<aa");
while(<FH>) {
    chomp $_;
    anagramCalculator($_);
}
sub anagramCalculator {

    my $str = shift;
    my $length = length($str);
    say "length is $length";
    my $char_suffling;
    if ( $length % 2 == 0 ) {
        my $s1 = substr($str,0,$length/2);
        my $s2 = substr($str,$length/2);
        my @s1_list = sort split('',$s1);
        my @s2_list = sort split('',$s2);
        for(my $i=0; $i<=$#s2_list;$i++) {
            say "iter num $i";
            for ( my $j = 0; $j<=$#s1_list; $j++) {
                if ( $s1_list[$j] eq $s2_list[$i] ) {
                    splice(@s1_list,$j,1);
                    splice(@s2_list,$i,1);
                    $i = -1;
                    last;

                }
                    
            }
            #say "iter count $i $s1_list[$i]";
            #if ( grep { $_ eq $s1_list[$i]}@s2_list){
            #        say "$i found in $s1_list[$i]";
            #        my $e = splice(@s1_list,$i,1);
            #        #say "element removed is $e";
            #        #say "iter count is $i ";
            #        $i = -1;
            #}
            
        }
        #say Dumper \@s1_list;
        #say Dumper \@s2_list;
        $char_suffling = scalar(@s1_list);
    } else {
        $char_suffling = -1;
    }
    say "$char_suffling";
}
