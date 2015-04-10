use threads;
use threads::shared;

my $total :shared = 1;
sub calc {
    while (1) {
    my $result;
    # (... do some calculations and set $result ...)
    {
        lock($total); # Block until we obtain the lock
        $total += $result;
    } # Lock implicitly released at end of scope
    last if $result == 0;
    }
}
my $thr1 = threads->create(\&calc);
my $thr2 = threads->create(\&calc);
my $thr3 = threads->create(\&calc);
$thr1->join();
$thr2->join();
$thr3->join();
print("total=$total\n");
