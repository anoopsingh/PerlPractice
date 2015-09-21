#!/usr/bin/python


def searchItem(args, item):

    li = args
    srch_num = item

    length_of_array = len(li)
    if length_of_array % 2 == 0:
        mid_point = length_of_array/2
    else:
        mid_point = length_of_array + 1
        mid_point = mid_point/2
    print mid_point

    if li[mid_point] == srch_num:
        print "Item is found at index ", mid_point
    elif li[mid_point] > srch_num:
        searchItem(li[0:mid_point],srch_num)
    elif li[mid_point] < srch_num:                        
        searchItem(li[mid_point:],srch_num)                        

 
sorted_array = [12,23,45,67,89,90,189]
searchItem(sorted_array,12)
