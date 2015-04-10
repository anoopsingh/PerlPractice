#!/usr/bin/perl

my $array_ref = [
                   [ "fred", "barney", "pebbles", "bamm bamm", "dino", ],
                   [ "homer", "bart", "marge", "maggie", ],
                   [ "george", "jane", "elroy", "judy", ],
                ];


foreach my $item ( @$array_ref ) {

     foreach ( @$item ) {
           print $_ ."\n";
     }
}

