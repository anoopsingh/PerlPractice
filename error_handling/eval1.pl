#!/usr/bin/perl

use 5.10.0;

#while ( $line = <> ) {
#    $str .= $line;
#    break;
#
#}
#eval $str;


$str = '$c = $a + $b';
$a = $b = 10;

print $str;
eval $str;
print $c;

$str = '$a++; $a + $b';

$a = 10; $b = 20;
say "\n";
$c = eval $str;

say $c;


eval {
   $a = 10;
   $b = 0;
   $c =  $a/$b;

};

say $@;



sub open_file {

    open(F, $_[0]) or die "no such file $!";

}

$f = 'aa.dat';
$ff = 1;
while($ff) {

    eval {
        open_file($f);
    };
    last unless $@;
    say "$f is not present  enter new file";
    chomp ($f = <STDIN>);
}

$str = '$c = 10';
eval $str;
say $str;
eval "$str";
say $str;
$c = eval'$str';
say $c;
$c = eval {$str};
say $c;









