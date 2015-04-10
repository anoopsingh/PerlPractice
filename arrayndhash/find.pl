$files = `find . -type f`;
@list = split(/\n/,$files);
$cnt =0;
foreach ( @list ) {
    if ( $_ =~ /\.qmt$/ ) {
       if (  $_ =~ m/(.*)_[0-9]{1,4}\.qmt$/ ) {
           print "$_\n";
           $cnt++;
       }
    }

}
print "total tc is $cnt\n";
