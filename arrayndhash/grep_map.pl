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


## counts array elements that matches a pattern
my @fruit = qw/anoop grapes anoopsingh banana mango/;
my $num_match = grep /^anoop/i,@fruit;
my @num = grep /^anoop/i,@fruit;

print "match count is  $num_match\n";
print Dumper \@num;


## extract unique element from a list

my @ele = qw /a d gf s g e d  s a t e e d f h k l k g/;
my %count;
my @unique = grep { ++$count{$_} <2} @ele;

print Dumper \@unique;


## extract the element whic are duplicate in a list

my @crops =qw(wheat corn barley rice corn soybean hey alfalfa rice hey corn hey);

my @dupes = grep{ $count{$_} ==2} grep {++$count{$_}>1} @crops;

print Dumper \@dupes;
my @dupes = grep{ $count{$_} >1} @crops;
print Dumper \@dupes;


## list text files in current directory


my @text_files = grep { -f and -T} glob '*.*';

print Dumper \@text_files;


## select array element and remove duplicates
my %counts = ();
my @array_line = qw(to be or not to be that is the question);

my @found_word = grep { $_ =~/b|o/i and ++$counts{$_} < 2;} @array_line;

print Dumper \@found_word;




## selects elements from a 2-D array where y>x

my @data_points = ([5,12],[20,-3],[2,2,],[13,20],[45,23]);

my @y_gt_x = grep { $_->[1]>$_->[0]} @data_points;

print Dumper \@y_gt_x;



#Search a simple database for restaurants  

# @database is array of references to anonymous hashes 
my @database = ( 
    { name      => "Wild Ginger", 
      city      => "Seattle",
      cuisine   => "Asian Thai Chinese Japanese",
      expense   => 4, 
      music     => "\0", 
      meals     => "lunch dinner",
      view      => "\0", 
      smoking   => "\0", 
      parking   => "validated",
      rating    => 4, 
      payment   => "MC VISA AMEX", 
    },
    { name      => "Anoop SIngh", 
      city      => "Azamgarh",
      cuisine   => "baati chokha",
      expense   => 4, 
      music     => "\0", 
      meals     => "lunch dinner",
      view      => "\0", 
      smoking   => "\0", 
      parking   => "validated",
      rating    => 4, 
      payment   => "MC VISA AMEX", 
    },
#    { ... },  etc.
);

sub findRestaurants {
    my ($database, $query) = @_;
    return grep {
        $query->{city} ? 
            lc($query->{city}) eq lc($_->{city}) : 1 
        and $query->{cuisine} ? 
            $_->{cuisine} =~ /$query->{cuisine}/i : 1 
        and $query->{min_expense} ? 
           $_->{expense} >= $query->{min_expense} : 1 
        and $query->{max_expense} ? 
           $_->{expense} <= $query->{max_expense} : 1 
        and $query->{music} ? $_->{music} : 1 
        and $query->{music_type} ? 
           $_->{music} =~ /$query->{music_type}/i : 1 
        and $query->{meals} ? 
           $_->{meals} =~ /$query->{meals}/i : 1 
        and $query->{view} ? $_->{view} : 1 
        and $query->{smoking} ? $_->{smoking} : 1 
        and $query->{parking} ? $_->{parking} : 1 
        and $query->{min_rating} ? 
           $_->{rating} >= $query->{min_rating} : 1 
        and $query->{max_rating} ? 
           $_->{rating} <= $query->{max_rating} : 1 
        and $query->{payment} ? 
           $_->{payment} =~ /$query->{payment}/i : 1
    } @$database;
}

my %query = ( city => 'Azamgarh', cuisine => 'baati chokha' );
my @restaurants = findRestaurants(\@database, \%query);
print Dumper \@restaurants;
print "$restaurants[0]->{name}\n";
