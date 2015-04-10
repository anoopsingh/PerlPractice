#! /usr/bin/perl

use Data::Dumper;


# example of merging two hashes
my %hash1 = (
   'key1' => 'Value 1',
   'key2' => 'Value 2',
   'key3' => 'Value 3',
);


my %hash2 = (
   'key4' => 'Value 4',
   'key5' => 'Value 5',
   'key6' => 'Value 6',
);

#merging in new hASH
my %newhash = (%hash1, %hash2);

## merging in one of the hash
my %hash1 = (%hash1, %hash2);

print "merge in new hash\n";
print Dumper \%newhash;
print "merger in one of the hash\n";
print Dumper \%hash1;


foreach my $key ( keys %newhash ) {
    print "key is $key value is $newhash{$key}\n";

}


## merging of two arrays

my @array1 = ('adadad', 'zfazfaf',5353);
my @array2 = ('lllll', 'ggggg',242);

my @newarray = (@array1, @array2);
my @array1 = (@array1, @array2);

print Dumper \@newarray;

foreach (@newarray) {

  print "Values of the array is $_\n";
}
