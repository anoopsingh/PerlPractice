#!/gats/bin/perl -w

use strict;
use warnings;
use 5.10.0;

my $file = 'anoop_file.txt';#$ARGV[0];


########################  File read operation
my $cnt = 0;
unless ( defined $file ) {

   say "Please specify the file name, file name argument is missing";

} else {
    # edit file without truncating it.
    open(FH,"+<$file") or die "Cant open file, $!\n";
    ## moves the file pointer to specified location
    seek FH, 107, 0; 
    while(<FH>) {
        my $pt = tell FH;
        say $pt;
        chomp $_;
        $cnt++;
        #say $_;
    
    }
    
    print FH "Appended String Anoop\n";
    say "Total number of lines in file is $cnt";
    close(FH);
}

my (@description, $size);

if ( -e $file ) {
   push @description, 'binary' if (-B _);
   push @description, 'a socket' if (-S _);
   push @description, 'a text file' if (-T _);
   push @description, 'a block special file' if (-b _);
   push @description, 'a character special file' if (-c _);
   push @description, 'a directory' if (-d _);
   push @description, 'executable' if (-x _);
   push @description, (($size = -s _)) ? "$size bytes" : 'empty';
   print "$file is ", join(', ',@description),"\n";

}
## read function
eval {
    rename($file, 'anoop_file.txt');

};

if ( $@ ) {
    say " $!";

}
unlink('ll');
