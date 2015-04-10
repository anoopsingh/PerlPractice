use threads;
my $a :shared = 2;
my $b :shared;
my $c :shared;
my $thr1 = threads->create(sub { $b = $a; $a = $b + 1; });
my $thr2 = threads->create(sub { $c = $a; $a = $c + 1; });
print $thr1->join();
print "\n";
print $thr2->join();
