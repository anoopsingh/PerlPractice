#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;



my $word_count = 0;
takeInputFromSTDIN();

sub takeInputFromSTDIN {

    say "Enter the input lines for which you want to calculate the wird count";
    my $line = <>;
    while($line ne '') {
        chomp $line;
        my $return= wordCountInALine( $line );
        $word_count = $word_count + $return;
        say "Total words in line : $line is $word_count";
        $line = <stdin>;
    }

}
# ////////////////////////////////////////////////////////////////////

=head word_count

Parameters    : stream of words say line
Return        : Sun of all words in that line
Description   : This function will basically split the libes in wors seperated by space             into a list

=cut


sub wordCountInALine {

    my $l = shift;
    my @word_list = split(/[\t ]+/, $l);
    my $size = scalar(@word_list);
    return $size;

}
