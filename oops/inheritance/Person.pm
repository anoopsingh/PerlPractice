package Person;

use strict;
use warnings;

use Data::Dumper;

sub new {

    my $class = shift;
    my $self = {
                 _firstName => shift,
                 _lastName => shift,
                 _ssn => shift
                };
    bless $self, $class;
    return $self;
}

sub setFirstName {
    my $self = shift;
    $self->{_firstName} = shift;
}

sub getFirstName {

    my $self = shift;
    return $self->{_firstName};
}

1;
