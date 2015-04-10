#! /usr/bin/perl

use strict;
use warnings;
use 5.10.0;
use Data::Dumper;


my @AOA = ( ['sfs', 'szff','cbcbnc'],
            [333,333,76],
            ['rrwer', 'wtwt', 'rtu']
          );

print "$AOA[0][1]\n";
print Dumper \@AOA;


foreach my $item ( @AOA ) {
    print "item is @$item\n";
}


### sorting of hashes

## important to note that <=> work on itegers and cmp works on strings
#<=> checks  numericall less that equal to gggrgeater than
#cmp checks string wise


my @arr1 = (1, 'two', 3, 9, 0, 4, 'eight', 5, 'six', 7, 8, 9, 10);


my @arr3 = qw(ram anoop singh one teo eight four roberst Alient Bombay);

my @arr4 = sort {$a cmp $b} @arr3;

print "\n array after sorting is \n";
print "\n @arr4\n";

my @sorted_array = sort {uc $a cmp uc $b} @arr3;
print "\n array after case sensitive sorting is \n";
print "\n @sorted_array\n";


my @list_str = qw( afaf afaf afasfs fsfs afaf ssssrad);

my @sorted_list = sort {$a cmp $b} @list_str;

print "soritn using <=> is @sorted_list\n";


# sorting an array of numbers

my @num = qw(46 22 755 724 9 24 0 2425 5854);
my @srtarr = sort {$a <=> $b} @num;

print "\n sorted array is @srtarr ------------------\n";

### sorting array backwards;

my @string = qw(ram anoop singh jackal john);

my @str1 = sort @string;

print "sorted str array in forward dir is @str1\n";

my @str2 = sort {$b cmp $a} @string;

print "\n sorted str in backward dir is @str2 -------------\n";


## sorting hash by keys

my %hash = ( c=>1, java=>23, perl=>2, linux=>22, hacking=>45);

    print "sorting hash by keys\n";
foreach my $lang ( sort keys %hash) {

    print "\n lang is $lang and value is $hash{$lang}-----------> \n";
}

### sorting hash by values

print "sorting hash by values\n";
foreach my $lang ( sort {$hash{$a} <=> $hash{$b}} keys %hash) {
    print "\n lang is $lang and value is $hash{$lang}-----------> \n";

}


## sorting complex data structure

my @genius = ( { name=> 'ram',age=>28},
               {name=>'shyam', age=>34},
               {name=>'gandhi', age=>45},
               {name=>'subhas', age=>23},
               {name=>'bhagat', age=>11},
               {name=>'diar', age=>2},
               {name=>'teresa', age=>8});

    print "\n =======> Sorting complex data structure\n";
foreach my $person (sort {$a->{name} cmp $b->{name}} @genius ) {

    print "\n =======> $person->{name}\n";
    print "\n =======> Name : $person->{name} age is $person->{age}\n";

}

print "sorting by age\n";
foreach my $person (sort {$a->{age} <=> $b->{age}} @genius ) {

    print "\n =======> Name : $person->{name} age is $person->{age}\n";

}





## sorting same hash by age using fucnction



foreach my $person ( sort agecmp @genius ) {
    print "name is $person->{name} and age is $person->{age}\n";

}

sub agecmp {

 return $a->{age} <=> $b->{age};

}



## sort a hash by keys

my %hash11 =(anoop=>'singh',abhay=>'kumar',dainik=>'jagran',italy=>'milan');

foreach (sort keys %hash11 ) {

    say "key $_ value $hash11{$_}";
}


my @sorted = map { { ($_ =>$hash11{$_})}} sort keys %hash11;

foreach my $hashref ( @sorted ) {

   ( my $key, my $value) = each %$hashref;
   say "$key => $value";
}

## sort hash by values

foreach my $key ( sort { $hash11{$a} cmp $hash11{$b}} keys %hash11) {

    say "$key => $hash11{$key}";

}


my @sorted = map { { ($_ => $hash11{$_}) } } 
              sort { $hash11{$a} cmp $hash11{$b}
                     or $a cmp $b
                   } keys %hash11;
foreach my $hashref (@sorted) {
    my ($key, $value) = each %$hashref;
    print "$key => $value\n";
}


@sorted = map {{($_ =>$hash11{$_})}} sort { $hash11{$a} cmp $hash11{$b} or $a cmp $b} keys %hash11;

foreach my $hashref ( @sorted ) {

    my ( $key, $value ) = each %$hashref;
    say "key $key => value $value";

}


