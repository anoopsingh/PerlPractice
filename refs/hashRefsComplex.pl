use strict;
use 5.10.0;
use Data::Dumper;

my $hr;


my $drinks = ['cappuccino', 'espresso', 'chocolate'];
my $thrist = ['thirst'];
my $serious = ['godan'];
my $slash_books = ['camel book', 'panther book', 'algorthim theory'];
$hr->{left}->{'coffeemachine'}->{'money'} = $drinks;
$hr->{left}->{'coffeemachine'}->{nomoney} = $thrist;
$hr->{right}->{'library'}->{serious} = $serious;
$hr->{right}->{library}->{'slacker'} = $slash_books;

say Dumper($hr);
