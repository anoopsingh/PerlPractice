#!/usr/bin/perl

package Fruit;
use strict;
use warnings;
use Data::Dumper;

sub new {

    my $class = shift;
    my $self = {};
    bless $self, $class;
    return $self;
}

sub Hi {

    print "Hi I ma insdie HI method\n";

}

package main;

my $obj = new Fruit();

$obj->Hi;
