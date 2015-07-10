use Override;

my $obj = new Override("Kumar", "Singh", "0007");

my $ret = $obj->getFirstName();
print "First name is $ret\n";
$obj->setFirstName("Ritu");
my $ret = $obj->getFirstName();
print "First name is $ret\n";
my $ret = $obj->getLastName();
print "last name is $ret\n";
$obj->setLastName("Thakur");
my $ret = $obj->getLastName();
print "last name is $ret\n";
