#!/usr/bin/perl -w

use strict;
use warnings;

use 5.10.0;
use Data::Dumper;

$a = 0.00 .'';
say $a;

if (0.00.''){
    say "$a is true";
} else {
    say "$a is false";
}

say "Arguments:".@ARGV;
say map { $_ > 2, "\n"} @ARGV;
my @aa;
map { push(@aa,$_) if $_ > 10 ? $_: 0, "\n"} @ARGV;
say Dumper \@aa;


my @names = qw (anoop dipika advay dev amma);

my %hash = map { $_=>length($_)}@names;

while (my ($key, $val) = each %hash) {
    say "Name $key and length $val";

}

my @array = ({
        id      => 1,
        name    => 'Bob',
    },{
        id      => 2,
        name    => 'Anne',
    },{
        id      => 3,
        name    => 'Frank'
    });


my %ha = map { $_->{id} => { name => $_->{name} }} @array;

say Dumper \%ha;


for my $id ( 1 .. 3) {
    say "User id is $id and name is $ha{$id}{name}";
}


say ("----|" x 8);


my $name = 'Anoop Kumar Singh';

my $last = substr($name,-1,4);
say "last char is $last $name";
my $l = length($name);
say "$l --------------------";
my $last1 = substr($name,0,1);
say "last char is $last1";

$last=substr($name,16,1);
say "$last ===============";
$last=substr($name,10);
say "$last ===============";

$name = 'dipika singh';

#substr($name,0,1)=uc(substr,$name,0,1);
say "$name ------------ upper case";
substr($name,-2) = '';
say "$name ------------ upper case";
$name = substr($name,0,-2);
say "truncaste ----------- $name ";
say substr($name,2);

$name = "Advay";
substr($name,0,0) = 'Mr ';
say "$name -------------------->";


## convert first three letters to upper case

substr($name,0,7) =~ tr/a-z/A-Z/;

say "$name is --------------upper case 3";

my $string = 'abbba';

substr($string,1,3,'xxx');
say "$string ====="; 
substr($string,1,3) = 'YYY';
say "$string ====="; 

substr($name,1,3) =~ tr/A-Z/a-z/;
say "$string 111111111====="; 




my $hh = ' Hello ';
substr($hh,length($hh),-1,'World');
say "adding substrin in str $hh";


### --------------- String searching : index and rindex function ------------------ ###

$string="abracadabra";

say index($string, 'ra');
say rindex($string, 'ra');
say rindex($string, 'rawss');
say index($string, 'ab', 2);

my $a=qq(<font face=font" color="color">);
say "quoted string --------- $a";

my $fullname = qq(C:/WINDOWS/TEMP/SOME.DAT);
$last = rindex($fullname,'/');
my $file_name = substr($fullname,($last+1));
say "file name is $file_name";
### ------------------- Length of the string: function length----------------------------- ###

my $pass = <stdin>;
if(length($pass) <8 ) {

    say "Bad/Invalid password";
}

my $s = 'addsdsddfssf';
$s = substr($s,0,5);
say "s iss ............. $s";

############################### transalte function #########################
$_ = 'Test 12345665674890';
my $k =tr/23445678/9/;
say $_;
say "$k kkkkkkkkkkkk ----";

my $kk=tr/999/1/;
say "$_ num------------";


#### Counting characters using tr ####


$_ = '172.23.54.205';
my $total = tr/.//;
say "toatl is $total";

$_ = 'adad afsf 3546 565 sfdg 33535 sfsf 53534643 ';
$total = tr/[0-9]//;
say "toatl is $total";
say "3333333333333333333333 $_";
$total = tr/[a-z]//c;
say "toatl is $total";
$k = tr/a-z0-9/A9/s;
say "$k---------------------------->";
$k = tr/a-z0-9/A/d;
say "$k---------------------------->";
say "$_ ---------------- =======";


$name = "name";

say uc($name);
say ucfirst($name);

sub trim {
   my $s = $_[0];
   $s=~s/^\s*(.*?)\s*$/$1/;
   say "$s=============================";
   return $s;
}
sub ltrim
{
   my $s = $_[0];
   $s =~ s/^\s+//;	
   return $s;
}
sub rtrim
{
   my $s = $_[0];
   return $_[0] =~ s/\s+$//;	
   return $s;
}


my $n = trim('     anoop      ');
say "$n --------------------";

sub scan {
   my $t = $_[0];
   if ($t =~s/\s*(\S+)\s+(.*)$/$2/ ) {
      say "1  is $1 and 2 is $2";
      say "t is $t";
      return $1;
   } else {
      return '';
   } # if
}

say scan(' SIngh Anoop kumar ');



#my $this_string=subword("Where is this string",3,2); # returns the string "this string" 
#my $third_word = subword("Where is this string",3) ;

$name='softpanorama';
my $ll = index($name,'s');

if ( $ll > -1 ) {
   say 'String  has "s" in it'; 
}

$string='softpanorama'; 
my @c = split(//, $string);
print "$c[0]$c[4]$c[2]$c[-3]$c[3]\n";
#spfat
my $str1='remember';
my $str2='Perl';
my $str3='warts';
my $left = $str1 . " " . $str2 . " " . $str3;
#remember perl warts
my $right = "$str1 $str2 $str3";

if ($left eq $right) {
  print "strings are equal\n";
} else {
  print "strings are unequal\n"
}
























