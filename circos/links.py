

acro = []
acro2 = []

lines = [line.strip() for line in open('names.tsv')]

for l in lines:
  tmp = l.split('\t')
  acro.append((tmp[0],tmp[1]))
  


import os, sys
usage = "usage: %s search_text replace_text [infile [outfile]]" %         os.path.basename(sys.argv[0])

output = sys.stdout
input = open('paths.txt')

for s in input.xreadlines():
  for x in xrange(len(acro)):
    s = s.replace(acro[x][1],acro[x][0])    
  output.write(s)

