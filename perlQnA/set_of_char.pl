#Provided a set of characters and a list of words, how many of those words can we create from the input characters ?
#    input:
#    chars = 'atuslirabocynkebbterasiuaracnbawp'
#    words = ['car','boat','plane','bus','shuttle']
#
#    output:
#    car => 2
#    boat => 1
#    plane => 1
#    bus => 2
#    shuttle => 0
#

#!/usr/bin/perl -w

use strict;
#use warnings;
use Data::Dumper;
use 5.10.0;


my $chars = 'atuslirabocynkebbterasiuaracnbawp';
my @words = ('car','boat','plane','bus','shuttle');
my $char_cnt = {};
my @tmp = split('',$chars);
#say Dumper \@tmp;
foreach my $char ( @tmp ) {
   if ( exists $char_cnt->{$char} ) {
       my $val = ($char_cnt->{$char} +1); 
       $char_cnt->{$char} = $val;
   } else {
       $char_cnt->{$char} = 1;
   }
}
say Dumper $char_cnt;
foreach my $wrd (@words) {
    my %t ;
    #say $wrd;
    my @tt = split('',$wrd);
    #say Dumper \@tt;
    foreach (@tt) {
        $t{$_} = $char_cnt->{$_};
    }
    my @key = values %t;
    @key = sort @key;
    #say Dumper \@key;
    my $times;;
    if ( !defined $key[0] ) {
         $times = 0;
    } else {
          $times = $key[0];
    } 
    say " $wrd can be formed $times times";
    foreach ( %t ) {
        $char_cnt->{$_} = ($char_cnt->{$_} - $times); 
    }

}













