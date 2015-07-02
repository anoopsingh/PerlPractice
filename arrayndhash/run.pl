my $str = 'adasf , saf 232 fsafsfs 342 safsaf';

if ( $str =~ /.*(\d\d\d).*(\d\d\d).*/) {

   #print "1 === $1 2 ======== $2 3=========== $3 4========= $4\n";
   print " 2 ========$1 4=========$2\n";
}
