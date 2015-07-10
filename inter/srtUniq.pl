#!/usr/bin/perl


@array1 = ("tea","coffee","tea","cola","coffee");

%ha;
@uniq = grep { ++$ha{$_} < 2} @array1;

print "@uniq"."\n";
%hash = map { $_ => 1 } @array1;

@aa = keys %hash;

print "@aa"."\n";
