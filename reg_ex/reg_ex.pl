#!/usr/bin/perl

use strict;
use warnings;
use 5.10.0;

my $input_str = $ARGV[0];

matchOnly_A_and_B();
string_do_not_contain_while_space();
anyString();
no_string_match();
sub matchOnly_A_and_B {

     if ( $input_str !~ m/(a|b)+/ ) {
       say "contains only a and b";
     } else {
       say "dose not contain a and b";
     }

}


sub anyString {

   if ( $input_str =~ /./ ) {
       say "matches any string";
   }else {
       say "pattern does not match any string";
   }

}
sub string_do_not_contain_while_space {

     if ( $input_str !~ m/\s+/ ) {
         say "string $input_str  does not contains white space";
     } else {
         say "string $input_str  does contains white space";
     }
}

sub no_string_match {

     if ( $input_str =~ /^$/ ) {

          say "no string match ";
     } else {
          say "matched string";
     }

}
matchEvenNumOfA();
sub matchEvenNumOfA {

    if ( $input_str =~ m/^(a*ab*ab*)*$/ ) {
          say "Even number of a in pattern";
    } else {
          say "not Even number of a in pattern";
    
    }

}
