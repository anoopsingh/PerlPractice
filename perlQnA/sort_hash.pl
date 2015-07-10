#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
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
#    now u have to arrange the name with highest to lower score,
#    if score is same than in alphabetical order
    #expected output:
    # John
    # Donald
    # Alice
    # Carlos
    # Bob
my @list = map{ $_->{name } } reverse sort { $a->{score} <=> $b->{score} } @$users;
say Dumper \@list;

foreach( sort {$b->{score} <=> $a->{score} || $a->{name} cmp $b->{name}} @$users ) { print $_->{name}, "\n"; }

