#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


#https://www.hackerrank.com/challenges/morgan-and-a-string

my $num_of_tests = <>;
chomp $num_of_tests;
my @str_input_list;

foreach my $i ( 1 .. $num_of_tests) {
    my $str1 = <>;
    my $str2 = <>;
    chomp $str1;
    chomp $str2;
    push(@str_input_list, $str1);
    push(@str_input_list, $str2);
}

foreach my $ii ( 1 .. $num_of_tests ) {
    my $s1 = shift(@str_input_list);
    my $s2 = shift(@str_input_list);
    lexical($s1, $s2);
}
sub lexical {

    my $list1 = shift;
    my $list2 = shift;
     
    my $str = undef;
    while ( ($list1 ne '') || ($list2 ne '') ) {
        if ( ($list1 ne '') && ($list2 ne '') ) {
            my $list1_char1 = substr($list1,0,1); 
            my $list2_char1 = substr($list2,0,1);
            if ( ord($list1_char1) < ord($list2_char1) ) {   
                substr($list1,0,1) = '';
                $str = $str.$list1_char1; 
            } elsif ( ord($list1_char1) > ord($list2_char1)) {
            #} else {
                substr($list2,0,1) = '';
                $str = $str.$list2_char1;
            } elsif (  ord($list1_char1) == ord($list2_char1)) {
                my $flag = 1;
                my $c = 0;
                while ( $flag ) {
                    if ( ($list1 ne '')  && ($list1 ne '') ) {
                        my $l1 = substr($list1,$c,1);
                        my $l2 = substr($list2,$c,1);
                        if ( $list1 eq $list2 ) {
                            $str = $str.$l1;
                            substr($list1,0,1) = '';
                            $flag = 0;    
                        } elsif (  ord($l1) > ord($l2)) {
                            $l2 = substr($list2,0,1);
                            $str = $str.$l2;    
                            substr($list2,0,1) = '';
                            $flag = 0;
                        } elsif ( ord($l1) < ord($l2)) {
                            $l1 = substr($list1,0,1);
                            $str = $str.$l1;    
                            substr($list1,0,1) = '';
                            $flag = 0;
                        } else {
                            $c++;
                        }
                    }#end if length ()
                    
                }#end while
            }
        }elsif ( $list1 ne '') {
            my $aa = substr($list1,0,1) ;
            substr($list1,0,1) = '';
            $str = $str.$aa; 
    
        }elsif ( $list2 ne '' ) {
            my $bb = substr($list2,0,1);
            substr($list2,0,1) = '';
            $str = $str.$bb;
        }
    }
    say "$str";
}
