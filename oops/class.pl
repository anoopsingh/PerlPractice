#! /usr/bin/perl

use strict;
use warnings;

use Data::Dumper;

package Fruit;

sub new {

    my $class = shift;
    my $self = {
                 name => shift,
                 cost => shift,
                 weight => shift,
               };
    #if ( defined $_[0]) {
    #    $self->{name} = shift;
    #}
    #if ( defined $_[0] ) {
    #    $self->{weight} = shift;
    #}
    #if ( defined $_[0]) {
    #    $self->{cost} = shift;
    #}
    bless $self, $class;
}#End sub new


sub cost {

    my $self = shift;
    return $self->{cost};

}

sub name {
    my $self = shift;
    return $self->{name};
}

sub weight {
    my $self = shift;
    return $self->{weight};

}
package main;

my $apple = new Fruit("apple1.1", "Rs 240", "1.2kg");

print "apple weight is ", $apple->weight, "  apple cost is = ", $apple->cost, "   name = ", $apple->name, "\n";

exit(0);
