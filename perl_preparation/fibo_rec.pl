use strict;
use warnings;

print fibonacci(10), "\n";

sub fibonacci {
    my $number = shift;

    my @fib = ( 0, 1 );

    for ( $#fib + 1 .. $number ) {
        $fib[$_] = $fib[ $_ - 1 ] + $fib[ $_ - 2 ];
        print $fib[$_]."\n";
    }

    return $fib[$number];
}
