#! /usr/bin/perl


## Inheritence example

package Employee;

use Person;

use strict;
our @ISA = qw(Person);


# override constructor

sub new {

    my ( $class ) = @_;

    my $self = $class->SUPER::new($_[1], $_[2], $_[3]);
    $self->{_id} = undef;
    $self->{_title} = undef;
    bless $self, $class;
    return $self;

}

# override helper function

sub getFirsName {
  my ( $self ) = @_;

  print "this is helper child function\n";
  return $self->{_firstName};

}

sub setLastName {

    my ( $self, $lastName) = @_;

    $self->{_lastName} = $lastName if defined($lastName);
    return $self->{_lastName};

}

sub getLastName {

  my ($self) = @_;
  return $self->{_lastName};

}
1;
