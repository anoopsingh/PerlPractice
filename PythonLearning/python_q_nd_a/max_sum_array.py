#!/usr/bin/python


def max_sum(A):

    cur_max,tot_max = A[0], A[0]
    for x in A[1:]:
        cur_max = max(x,x+cur_max)
        tot_max = max(tot_max,cur_max)
    return tot_max 
a = [1, 2, -3, 3, -7, 5, 4, -1, 4, 5]

#a =[-2,1,-3,4,-1,2,1,-5,4]
aa = max_sum(a)
print aa
