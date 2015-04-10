$i = 1; print "$i\n"; # Prints "1"
package foo;
$i = 2; print "$i\n"; # Prints "2"
package main;
print __LINE__, " ",__FILE__," $i\n"; # Prints "1"
print $foo::i;
