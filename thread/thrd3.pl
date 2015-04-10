use threads;
my $thr1 = threads->new(\&thrsub, "test1");
my $thr2 = threads->new(\&thrsub, "test2");
sub thrsub {
    my ($message) = @_;
    sleep 1;
    print "thread $message\n";
}  

$thr1->join();
$thr2->join();

#### shared variables




