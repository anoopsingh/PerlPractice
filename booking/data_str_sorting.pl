#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;;
use Data::Dumper;

 my $users = [
        {
            name => 'John',
            score => 10,
        },
        {
            name => 'Bob',
            score => 1,
        },
        {
            name => 'Carlos',
            score => 5
        },
        {
            name => 'Alice',
            score => 5,
        },
        {
            name => 'Donald',
            score => 7
        }
    ];

#now u have to arrange the name with highest to lower score,
#if score is same than in alphabetical order

my %hash_user = ();

foreach my $item ( @$users ) {

    $hash_user{$item->{name}} = $item->{score};
}

foreach ( reverse sort{$hash_user{$a} <=> $hash_user{$b}} keys %hash_user) {
    say $_;
}
say map{$_->{name}}reverse sort{$a->{score} <=>$b->{score}} @$users
