package MyWeb::App;
use Dancer ':syntax';

our $VERSION = '0.1';

get '/' => sub {
    #template 'index';
    return 'Hello';
};

get '/' => sub{
    return {message => "First rest Web Service with Perl and Dancer"};
};

get '/users/:name' => sub {
    my $user = params->{name};
    return {message => "Hello $user"};
};
 
get '/users' => sub{
    my %users = (
        userA => {
            id   => "1",
            name => "Carlos",
        },
        userB => {
            id   => "2",
            name => "Andres",
        },
        userC => {
            id   => "3",
            name => "Bryan",
        },
    );

    return \%users;
};

dance;
true;
