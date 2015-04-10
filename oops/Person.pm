#! /usr/bin/perl

package Person;

sub new {

    my $class = shift;

    my $self = {
        _firstName => shift,
        _lastName  => shift,
        _ssn       => shift,
    };
    print "First name is $self->{_firstName}\n";
    print "Last name is $self->{_lastName}\n";
    print "SSN name is $self->{_ssn}\n";
    # print all the values just for clarification
    bless $self, $class;
    return $self;


}

sub getFirstName {
    my ($self) = @_;
    return $self->{_firstName};

}

sub setFirstName {
   my ($self,$firstName) = @_;
   $self->{_firstName} = $firstName if defined($firstName);
   return $self->{_firstName};

}

1;
