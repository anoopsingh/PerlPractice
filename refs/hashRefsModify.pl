use strict;
use 5.10.0;
my $hr;

$hr->{scalar} = 'a string';
$hr->{array} = ['one thing', 'two thing'];
$hr->{hash} = {'key'=>'value', one=>'two'};

$hr->{array}->[0] = 'this one modified';
say $hr->{array}->[0];
my $array_ref_from_hr = $hr->{array};
$array_ref_from_hr->[0] = 'one thing modified again';
say $hr->{array}->[0];
