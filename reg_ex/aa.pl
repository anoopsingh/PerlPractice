$bld = '10.0.0.0d3';
$bld =~ s/^V//i; #removing prefix V
$bld = lc $bld;
if ( $bld =~ /^(\d{1,2}\.\d)(\.\d)(\.\d)/) {
    $rel = 'rel' . $1;
    $branch = "$1$2";
    $build = "$1$2$3";
    $buildDir = "/var/builds/$rel/iserver";
    @bDirs = ("$buildDir/$branch/$bld", "$buildDir/$build/$bld");
    print $1;
    print "\n";
    print $2;
    print "\n";
    print $3;

}

foreach ( @bDirs ) {
    print "\n";
    print $_;
    print "\n";

}
