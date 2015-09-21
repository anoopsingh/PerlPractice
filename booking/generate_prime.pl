sub sieve {
grep{@_[map$a*$_,$_..@_/($a=$_)]=0if$_[$_]>1}@_=0..pop
}

print join " ", sieve(1000000);
