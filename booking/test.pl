$num = 'szfsdfgdsgdsfsf';
print "-------------$num\n";
while ( length($num) > 0 ) {
    $k = substr($num, 0, 1);
    substr($num, 0, 1) = '';
    print "-------------$k\n";
    print "-------------$num\n";
}
