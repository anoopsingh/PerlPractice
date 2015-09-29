sub fib_r {
  my ($n,$a,$b) = @_;
  print "$b\n";
  if ($n <= 0) { 
       return $a; 
   }  else { 
      return fib_r($n-1, $b, $a+$b); 
   }
}

sub fib { 
     fib_r($_[0], 0, 1); 
}  # pass initial values of a and b

print fib(10), "\n";
