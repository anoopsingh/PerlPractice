#!/usr/bin/perl -w
use strict;
use warnings;
use Data::Dumper;
use Tie::RefHash;
use 5.10.0;

my $push = 'pop on';
say "${push}over";

my $c = "${push}over";
say $c;


tie my%h,'Tie::RefHash';
%h = (["this", "that"]=>"at home", ["that", "there"] =>"else where");


while(my ($k,$v) = each %h) {
    say "@$k is $v";
}
say "--------------------";

while ( my ($k, $v) = each %h ) {
      say "@$k is $v";
}
