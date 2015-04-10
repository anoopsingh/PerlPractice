
my $ip = '172.23.58.6';


if ( $ip =~ /^(\d\d?\d?)\.(\d\d?\d?)\.(\d?\d?\d?)\.(\d?\d?\d?)$/ ) {
    if (($1<=255) && ($1<=255) && ($3<=255) && ($4<=255)) {
         print "valid IP is $ip\n";
         eval {
             $result = `ping -c 4  -I 10.201.2.19 $ip`;
             $out = `echo $?`;
         };
         if ( $@ ) {
             print $@;
         }
    }

}
