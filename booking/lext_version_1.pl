#!/usr/bin/perl


# Enter your code here. Read input from STDIN. Print output to STDOUT
my $num_of_tests = <>;
chomp $num_of_tests;
my @str_input_list;

foreach my $i ( 1 .. $num_of_tests) {
    my $str1 = <>;
    my $str2 = <>;
    chomp $str1;
    chomp $str2;
    push(@str_input_list, uc($str1));
    push(@str_input_list, uc($str2));
    #say $i;

}
foreach my $ii ( 1 .. $num_of_tests ) {
    my $s1 = shift(@str_input_list);
    my $s2 = shift(@str_input_list);
    lexical($s1, $s2);
}
sub lexical {

    my $list1 = shift;
    my $list2 = shift;
    my @A = split('',$list1);
    my @B = split('',$list2);
    my $str = undef;
    while ( (scalar(@A) > 0) || (scalar(@B) > 0) ) {
        if ( @A && @B ) {
            if ( ord($A[0]) < ord($B[0]) ) {
                my $aa = shift(@A);
                $str = $str.$aa;
            } else {
                my $bb = shift(@B);
                $str = $str.$bb;
            }
        }elsif ( @A) {
            my $aa = shift(@A);
            $str = $str.$aa;

        }elsif ( @B ) {
            my $bb = shift(@B);
            $str = $str.$bb;
        }
    }
    print "$str\n";
}
