use Employee;

my $obj = new Employee("Kumar", "Singh", "0007");

my $ret = $obj->getFirstName();
print "First name is $ret\n";
$obj->setFirstName("Ritu");
my $ret = $obj->getFirstName();
print "First name is $ret\n";
