#! /usr/bin/perl

use strict;
use warnings;
use feature qw/say/;
use WWW::Selenium;
use YAML::XS qw/LoadFile/;
use Data::Dumper;

my $config   = LoadFile('config.yaml');

say Dumper $config;
my $vlanUrl  = $config->{vlanUrl};
my $userName = $config->{credentials}->{username};
my $password = $config->{credentials}->{password};

## Create the object of selenium
my $vlanObj = WWW::Selenium->new(host => "localhost",
                                  port => 4444,
                                  browser => "*firefox",
                                  auto_stop =>0,
                                  browser_url => "$vlanUrl",);

## opent the url with newly created object
$vlanObj->start;

## Open the VLAN management Url
$vlanObj->open("$vlanUrl");

# Now Enter thr credentials
$vlanObj->type('username',"$userName");
$vlanObj->type('password',"$password");

# submit the login form
$vlanObj->click('css=input[type="submit"]');
sleep(10);

## Wair for few seconds to load the home page
$vlanObj->wait_for_page_to_load(30000);

## Action performed in order to land on the vlan addition page
$vlanObj->click('//area[9]');
$vlanObj->wait_for_page_to_load(30000);
$vlanObj->click('link=IBM Networking OS RackSwitch G8052');
$vlanObj->wait_for_page_to_load(30000);
$vlanObj->click('link=Layer 2');
$vlanObj->wait_for_page_to_load(30000);
$vlanObj->click('link=Virtual LANs');
$vlanObj->wait_for_page_to_load(30000);

## Here initialize the vlan starting index
## Upto the last range value
my $startRange = $config->{vlanRange}->{start};
my $endRange = $config->{vlanRange}->{end};

## Extract the action from the config values
my $action = $config->{action};

#Depending upon the action vlans will be eitehr created of deleted

## Iterate through the number of vlan needs to be created
if ( $action eq 'add') {
    for (my $ii = $startRange; $ii<=$endRange; $ii++) {

        eval {
            $vlanObj->click('link=Add VLAN');
            sleep(15);
            $vlanObj->wait_for_page_to_load(40000);
    
            ## Create the vlan ID
            $vlanObj->type('vlanID', $ii);
    
            ## Create the VLAN name
            my $vlanName = 'VLAN '.$ii;
            $vlanObj->type('vlanName', "$vlanName");
    
            # select the vlan port
            #TODO as of now it is selecting port 2
            # On requirement basis that configuration can be moved to config variables
            $vlanObj->add_selection('name=portsVlanAvail', 'label=2');
            $vlanObj->click('name=B1');
            sleep(5);
     
            # All the mandatory field has been filled
            # Now it's time submit the form for creating the vlan
            $vlanObj->click('name=Submit');
            sleep(10);

        };

        if ( $@ ) {
            say "vlan creation failed for id $ii below error occurred";
            say $@;
        } else {
            say __LINE__, "  " , __FILE__, " vlan with id $ii created successfully ";
        }

    } #END For Loop
}


### Delete the existing VLAN
if ( $action eq 'delete' ) {

    for (my $ii = $startRange; $ii<=$endRange; $ii++) {

        eval {
            sleep(10);
            $vlanObj->click("link=$ii");
            sleep(10);
            $vlanObj->click("name=delete");
            sleep(10);
        };

        if ( $@ ) {

            say "vlan deletion failed for id $ii due to below reason :";
            say $@;
        } else {
           say __LINE__, "  " , __FILE__, " vlan with id $ii deleted successfully ";
        }
      
    } #END of for loop for deleting the vlans
}
