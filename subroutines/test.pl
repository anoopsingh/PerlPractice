use Data::Dumper;

my @a1 = qw(424, 313, 3321321);
my @a2 = qw(3133313135, 121, 31313);
my @w = (@a1, @a2);
print Dumper \@w;
$ret = mergeArray(\@a1, \@a2);

print Dumper $ret;
#print Dumper @$ret;

sub mergeArray {
    #my ($arr1, $arr2) = @_;
    my $arr1 = shift;
    my $arr2 = shift;
    my @arr3 = (@$arr1, @$arr2);
    return \@arr3
    
}
