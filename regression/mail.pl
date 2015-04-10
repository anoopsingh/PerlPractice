#!/usr/bin/perl
 
$to = 'gb-automation@globallogic.com';
$from = 'regession@anoop.com';
$subject = 'Regression Started on ***********swisscom **********';
$message = 'regression test mail';
$message= "Regression done Successfully\n";
 
open(MAIL, "|/usr/sbin/sendmail -t");
 
# Email Header
print MAIL "To: $to\n";
print MAIL "From: $from\n";
print MAIL "Subject: $subject\n\n";
# Email Body
print MAIL $message;

close(MAIL);
