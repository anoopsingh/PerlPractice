use Data::Dumper;

my $ip = '172.23200.54.223';

if ( $ip =~ /(^\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/ ) {
   if ( ($1 <=255) && ($2 <=255) && ($3 <= 255) && ($4 <= 255) ) {
            print "it's a valid IP\n"
   } else {
            print "not a valid ip\n"
   }
} else {
   print "Not a valid IP \n";
}


my @num = (1 .. 6);

@dbl = map {$_ * 2} @num;


print "@dbl" ."\n";

## put array in hahs


@list = (122, 44,4453,435,5345);

%ha = map { $_ => 1} @list;

print Dumper \%ha;

@key = keys %ha;
print Dumper \@key;
@val = values %ha;
print Dumper \@val;

##### data type modification

my @browser = qw/aa bb vv ff ee/;

$browser[3] = 'anoop';

print Dumper \@browser;

@rem = splice ( @browser, 0 ,2, 'singh', 'pinku');

print Dumper \@browser;
print Dumper \@rem;


## add element in beging 

unshift(@browser, 'lllllllll');

print Dumper \@browser;
 $del = shift(@browser);
print Dumper \@browser;
