#!/usr/bin/perl -w
use strict;
use warnings;
use 5.10.0;
use Data::Dumper;

## crate a array with .. range
#multiple elemet with any itnget put it in other array

my @array = (0..10);
my @a = map { $_ * $_}@array;
say Dumper \@a;


my @name = qw(anoop kumar singh pinku anoop advay adyav kumar);;
### using map fucntion to populate name whcih starts with A|P
my  %nam = map { $_ =~ /^[a|p]/ ? ($_=>1) : () } @name;
say Dumper \%nam;

my %hash = map { $_ => 1 } @name;
my @list = map { $_ => 1 } @name;

say Dumper \%hash;
@name = keys( %hash );
say Dumper \@name;
#say Dumper \@list;

my @num = qw(434 2424 535 53323 2434 245 43253 25 3242 53);
my @even = grep ($_>1000, @num);
say Dumper \@even;

## use of splice
### replaces the array if replce string provided else remove the elements from array

my @browser = qw(IE opera mozzila chorme);
$" = "\n";
say @browser;
my @replaced = splice(@browser,0,2,'anoop','dipika');
say @browser;
say @replaced;

##### add element in the begining 
unshift(@browser,'kamal');
say Dumper \@browser;
$browser[0]= 'ritu';
say Dumper \@browser;
shift(@browser);
say Dumper \@browser;

############# validate ip

#my $ip = qq/172.23.54.205/;
my $ip = qq/172.23.54.205/;
say $ip;

if ( $ip =~ /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/ ) {
    if ( $1 <=255 && $2<=255 && $3<=255 && $4<=255 ) {
        say "ip $ip  is valid one";
    } else {
        say "not a valid ip";
    }
}


my @list = qw ( aa.jpg kk.gif ll.mpeg gg ad wfew rr.jpg);
my @image_list = grep(/\.jpg$/,@list);
say Dumper \@image_list;
my @not_image_list = grep ( !/\.jpg$/,@list);
say Dumper \@not_image_list;
my @merge_list = (@image_list,@not_image_list);
say Dumper \@merge_list;

my @files = qw/
find_diff.pl                  hr_round.txt           palindrome.pl
2.pl                  find_small_common_integer.pl  input_anangram.txt     perl_str.pl
anagram_challenge.pl  find_uniq_in_two_array.pl     input.txt              test.pl
anagram.pl            first_non_repeated_char.pl    largest_palindrome.pl  word_cnt.pl
booking.txt           get_max_word_count.pl/;

say Dumper \@files;
## creates array with fils name and its size
my @size = map { -s, $_} @files;
say Dumper \@size;
## creates hash with file size key
my %size = map { -s, $_} @files;
say Dumper \%size;
## creates hash map with file nameas key and file size as values
my %size = map { $_=>-s} @files;
say Dumper \%size;
## create array with file size
my @zz = map(-s,@files);
say Dumper \@zz;

## now sort the hash file by size

foreach my $f ( sort {  $size{$a} <=> $size{$b} } keys %size ) {
    say "$f $size{$f}";
}
say "===========================================";
foreach my $f ( sort keys %size ) {
    say "$f $size{$f}";

}
####### map fucntion for 10 time eaxh array element

my @digit = qw ( 12 42 12 3222 3221 3445 4 24 34);

my @ten_time = map {$_ * 10} @digit;

say Dumper \@ten_time;
my @a1 = map {  $_ % 2 ? () : $_}@digit;
my @a11 = map {  $_ % 2 ? $_ : () }@digit;
my @even = grep { not $_ % 2}@digit;
 say "-----------", Dumper \@even;
my @odd = grep { $_ % 2}@digit;
 say "-----------", Dumper \@odd;

## get the list of files

my @total_file = map { (-T) ? $_ : () } glob "*";
say Dumper \@total_file;

my @total_file = grep { -T } glob "*";
say Dumper \@total_file;

say "Even number using map ", Dumper \@a1;
say "Even number using map ", Dumper \@a11;

###wihle loop on hash


while ( my ($key, $value) = each %size) {
    say "$key  == $value";
}
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
