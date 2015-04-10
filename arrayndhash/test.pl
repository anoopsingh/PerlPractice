#!/usr/bin/perl -w

use strict;
use warnings;
use Data::Dumper;


## example of grep

my @list = qw(anoop.jpg ss.gif af ssgf kk.jpg mm.jpg 2344 55f);

my @jpeg_list = grep(/\.jpg$/, @list);
my @not_jpeg_list = grep (!/\.jpg$/, @list);

print @jpeg_list;
my $cnt = @not_jpeg_list;

my @mergelist = (@jpeg_list, @not_jpeg_list);

print "merge list iis \n";
print @mergelist;
print "\ncnt is    $cnt\n";
print "\n @not_jpeg_list \n";

## example of map

my @files = qw/array_eg.pl  arrayhash.pl  brbracQuo.pl  grep_map.pl  sorting_ds.pl/;

my @sizes = map(-s, @files);


foreach (@sizes) {

  print $_;
  print "\n";
}
print @sizes;
my %sizes;
map($sizes{$_} = -s, @files);


foreach my $key ( sort keys %sizes ) {

   print "key is $key ans size is $sizes{$key} \n";
}

# sort by sizes

foreach my $key ( sort {$sizes{$a}<=>$sizes{$b}} keys %sizes ) {

   print "file is $key and size is $sizes{$key}\n";
}

foreach my $values ( values %sizes ) {
     print "$values\n";

}


## the basic of map fucntion
## @result = map expression, list  @result = map {code}, list

my @times_ten = map $_*2, 1..10;

print @times_ten;





## uppercae conversion

my @name_list = qw/anoop singh amit ranu rani/;
my @list_upper = map {uc $_} @name_list;

print @list_upper;

## grep example @list = grep expression, list ============  @list = grep {code} list

my @even_list = grep { not $_ % 2} 1..10;

print "\n------------------ Grep Example \n";
print @even_list;


my @list_file1 = grep { -T} glob "*";

print @list_file1;


# another way to get list of files

my @files = map { (-T) ? $_ : ()} glob "*";


print @files;


#print out content of hash

print map "$_:$sizes{$_}\n",sort keys %sizes;


while (my ($key, $val) = each %sizes) { print "$key: $val\n" }



print map "$_:$sizes{$_} \n",sort keys %sizes;

while ((my $keys, my $val)= each %sizes) { print "$keys : $val\n";}




my $text = 'hello how are you i am you you wating for you mr singh how are the singh singh singh singh  things going on wat about your son';

my %word_seen = map {$_ => 1} split /\s+/,$text;

my @word_list1 = split(/\s+/, $text);

my %seen;

foreach ( @word_list1 ) {

    $seen{$_}++;

}

print Dumper \%seen;

my %file_size = map {$_ => -s} @files;

print map "$_:size $file_size{$_}\n", sort keys %file_size;

### blenidng a array in a single array

my @x = qw/fd f ffsf  fssf  sff sf/;
my @y = qw/ff fsf f sfs fsf s fs/;
my @z = qw/sezfs sfrst ssd fgtd dgd/;
## it create multidimensional array
my @xyz = map [$x[$_],$y[$_],$z[$_]], 0..$#x;
# it wil merger element in single array

print "hhhhhhhhhhhhhhhhhhhhh\n";
print Dumper \@xyz;
print "hhhhhhhhhhhhhhhhhhhhh\n";


my @ddd = (@x, @y, @z);
print Dumper \@ddd;


my %info = map {$_,[stat $_]} @files;
print Dumper \%info;

@x = map$_->[0],@xyz;
print Dumper \@x;


open(FH, "<test.pl") or die "can open file";

my @lines = map scalar (<FH>), 1..10;

print Dumper \@lines;
