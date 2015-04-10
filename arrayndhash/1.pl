#! /usr/bin/perl -w

use Data::Dumper;

my %hash1 = ( 'key1'=>1,'key2'=>2 );
my %hash2 = ( 'aaa'=> 22, 'mmm'=>544);

my %hash3 = (%hash1, %hash2);

print Dumper\%hash3;


foreach my $key ( keys %hash3 ) {

   print "key is $key value : $hash3{$key}\n";
}


#merging two arrays

my @a1 = (12, 'w35', 'w465', 3536, 'sft');
my @a2 = ('af', 'zddw35', 'wd465', '353da6', 'sft');

my @a3 = (@a1, @a2);

print Dumper \@a3;

foreach (@a3) {
    print "elements : $_\n"

}
