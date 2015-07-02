flag = True

while flag:

    try:
        input = int(raw_input("Please enter a integer"))
        print "You have entered "
        print input
        print "done+++++++++++++++++++++++++++++++++done"
        flag = False
    except ValueError:
        print "Kindly enter a integer"
    except KeyboardInterrupt:
        print "cntrl + c has been pressed please exit"
        break 
           
           
           
           
