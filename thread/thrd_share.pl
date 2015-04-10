#! /gtas/bin/perl -w

use threads;
use threads::shared;

my $a :shared = 1;

my $thr1 = threads->create(\&sub1);

my $thr2 = threads->create(\&sub2);

$thr1->join();
$thr2->join();

print "$a\n";

sub sub1 {
my $foo = $a;
$a= $foo + 1;
print $a;

}

sub sub2 {

my $bar = $a;
$a= $bar + 1;
print $a;
}
