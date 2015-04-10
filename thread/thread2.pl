#! /gats/bin/perl -w

use threads;

my ($thrd) = threads->create(\&sub1);
my @returnData = $thrd->join();

print ('Thread returned ', join (', ', @returnData), "\n");
sub sub1 {

    return (23, 54, 778); 


}
