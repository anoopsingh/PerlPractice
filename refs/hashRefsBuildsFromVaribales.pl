#/usr/bin/perl -w
use strict;
use warnings;

use Data::Dumper;
use 5.10.0;

my $scalar = 'scalar string ';
my @array = (12,323,43);
my %hash = (ada=>23, DADA=>433, AFF=>543);
my $hashRef->{scalar} = \$scalar;
$hashRef->{array} = \@array;
$hashRef->{hash}= \%hash;

say Dumper $hashRef;


foreach (@{$hashRef->{array}}) {

   say $_;
}

foreach ( keys %{$hashRef->{hash}} ) {

   say "key is $_ and value is $hashRef->{hash}->{$_}";
}
