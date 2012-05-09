from decimal import Decimal
import sys

l1 = open('links_1.txt','w')
l2 = open('links_2.txt','w')
l3 = open('links_3.txt','w')
l4 = open('links_4.txt','w')
l5 = open('links_5.txt','w')
l6 = open('links_6.txt','w')
l7 = open('links_7.txt','w')
l8 = open('links_8.txt','w')
l9 = open('links_9.txt','w')

acro = []
acro2 = []

lines = [line.strip() for line in open('testlink.txt')]

for i,l in enumerate(lines):
  tmp = l.split('\t')
  d = Decimal(tmp[7]) * Decimal('1e5')
  
  if d > Decimal('50') and d <= Decimal('1000'):
    print >>l1, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l1, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('1000') and d <= Decimal('4000'):
    print >>l2, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l2, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('4000') and d <= Decimal('5000'):
    print >>l3, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l3, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('5000') and d <= Decimal('6000'):
    print >>l4, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l4, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('6000') and d <= Decimal('7000'):
    print >>l5, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l5, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('7000') and d <= Decimal('8000'):
    print >>l6, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l6, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('8000') and d <= Decimal('9000'):
    print >>l7, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l7, "link{} {} 0 1".format(i,tmp[2]) 
  elif d > Decimal('9000') and d <= Decimal('10000'):
    print >>l8, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l8, "link{} {} 0 1".format(i,tmp[2])     
  if d > Decimal('10000'):
    #print l
    print >>l9, "link{} {} 0 1".format(i,tmp[0]) 
    print >>l9, "link{} {} 0 1".format(i,tmp[2]) 

#print "Max is ", max
  

