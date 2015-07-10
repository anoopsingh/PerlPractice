package Override;
use Person;
use strict;

our @ISA = qw(Person);

### override the constructor


sub new {

    my $class = shift;
    my $self = $class->SUPER::new($_[0],$_[1],$_[2]);
    $self->{_id} = undef;
    $self->{_title} = undef;

    bless $self, $class;
    return $self;
}

## override the helepr function

sub getFirstName {
    my ($self) = @_;
    print"inside class $_[0] method getFirstName\n";
    print "This is the child class helper function\n";
    return $self->{_firstName};

}

sub setFirstName {
    
    my ($self, $firstName) = @_;
    print"inside class $_[0] method setFirstName\n";
    print "setting up firstname in child fucntion\n";
    $self->{_firstName} = $firstName if defined ($firstName);
    return $self->{_firstName};
}

### add new function

sub setLastName {

    my ($self, $lastName) = @_;
    print"inside class $_[0] method setLastName\n";
    $self->{_lastName} = $lastName if defined($lastName);
    return $self->{_lastName};

}


sub getLastName {
    my ($self) = @_;
    print"inside class $_[0] method getLastName\n";
    print "Child funtion returning last name \n";
    return $self->{_lastName};

}

1;

