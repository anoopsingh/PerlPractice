#!/usr/bin/perl -w

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


my @a = qw(/ a b c);
push(@a, " ");
push(@a, "::");
$" = "\n";
say "@a";


#---------------------------


my $str = "foo bar foo bar foo bar zoo zoo fuzz fuzz bar";
my $c = () = $str =~ /foo|bar/g;
say $c;

my $a = substr($str,0);
say $a ;


my @array = qw ( anoop advay dipika anoop ritu pinku singh advay dipika anoop );

my @index = grep {$array[$_] eq 'dipika' } 0..$#array;
$" = "\n";
say @index;

######### code referecne
sub add_function_generator {

    return sub { shift() + shift() };
}

my $add_sub = add_function_generator();
say ref($add_sub);
my $sum = $add_sub->(4,5);
say $sum;



## ref subroutine

sub make_adder {
    my $addpiece = shift;
    return sub { shift() + $addpiece };
}

my $f1 = make_adder(20);
say $f1->(10);

############ Package variable #######

$main::foo = 'bar';
say $main::foo;
test();
say $main::foo;
sub test {

    $main::foo = 'foo';

}

# Regex validation for US-Number
my $ph0 = "(312) 456 7890";
my $ph1 = "(312)-456-7890";
my $ph2 = "(312).456.7890";
my $possible_seperator = " |-|.";

foreach ( $ph0, $ph1, $ph2) {
    if ( $_ =~ /^\(\d{3}\)$possible_seperator\d{3}$possible_seperator\d{4}$/) {
       say "$_ Number is matched";
    }
}

my %hash = (

    a => 12,
    w => 543,
    r => 34,
    k => 24
)

my @keys = keys %hash;
my @values = values %hash;

foreach my $key ( sort keys %hash ) {
    say "key $key value $hash{$key}";

}

foreach my $key ( sort { $a cmp $b}keys %hash














