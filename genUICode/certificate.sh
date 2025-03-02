#!/bin/sh
SERVER=server
PRIVATE_KEY=$SERVER.private.key
CERTIFICATE_FILE=$SERVER.crt
VALID_DAYS=365

echo Delete old private key
rm $PRIVATE_KEY
echo Create new private/public-keys without passphrase for server
openssl genrsa -out $PRIVATE_KEY 1024

echo Create selfsigned certificate
rm $CERTIFICATE_FILE
# From man req:
#  -x509
#    this option outputs a self signed certificate instead
#    of a certificate request. This is typically used to
#    generate a test certificate or a self signed root CA.
#    The extensions added to the certificate (if any) are
#    specified in the configuration file.

openssl req -new         -days $VALID_DAYS         -key $PRIVATE_KEY         -x509         -out $CERTIFICATE_FILE

echo private-keyfile is $PRIVATE_KEY
echo server-certificate-file is $CERTIFICATE_FILE

ls -l $PRIVATE_KEY $CERTIFICATE_FILE
