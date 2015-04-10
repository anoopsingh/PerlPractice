#!/usr/bin/perl
use Tie::RefHash;

tie my %ref, 'Tie::RefHash';

my $ref = [
           ["anoop","kumar","singh"],["amit","chintu"],["dipika","ritu"]
          ];

foreach my $item (@$ref ) {
     foreach (@$item) {
         print $_ ."\n";
     }
}
    
