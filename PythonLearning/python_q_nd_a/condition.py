try:
    x = int(raw_input("Please enter a integer"))
    
    if x < 0:
        x = 0
        print "negative numbered has been entered"
        print x
    elif x == 0:
        x = 'Zero'
        print "Zero has been entered"
    elif x == 1:
        print "Single"
    else:
        print "more"
    
except ValueError:
    print "Enter a integer not a string"
