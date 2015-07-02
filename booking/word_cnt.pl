#!/usr/binperl -w

use strict;
use warnings;
use Data::Dumper;
use 5.10.0;

open(FH, "<input.txt") or die "Can not opent the file $!";


my $hash_ref = {};

while(<FH>) {

    chomp $_;
    my @temp = split(" ", $_);
    foreach (@temp) {
        if ( exists $hash_ref->{$_} ) {
            #say "$_ is presnet";
            my $cnt = $hash_ref->{$_};
            $hash_ref->{$_} = ( $cnt + 1 );
        } else {
            $hash_ref->{$_} = 1;
            #say " $_ ::  Do not exist new word"
        }
    }

}#END while

#foreach ( sort keys %$hash_ref ) {
#    say "key :: $_  value :: $hash_ref->{$_}";
#}

my $cnt = 0;
foreach my $item ( sort {$hash_ref->{$b} <=> $hash_ref->{$a}} keys %$hash_ref){
    if ($cnt <= 4) {    
        say " key $item Value $hash_ref->{$item}";
        $cnt ++;
    } else {
        exit;
    }
}
