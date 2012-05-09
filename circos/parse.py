

acro = []
acro2 = []
name = []

lines = [line.strip() for line in open('pathways.txt')]

for l in lines:
  ab = l.split('\t')
  s = ''.join(i for i in l if (i.isalpha() or i == ' ' ))
  acr =  ''.join(x[0] for x in s.split()).upper()
  if not (acr in acro):
    acro.append(acr)
    acro2.append((acr,ab[1]))
    name.append(ab[0])
  else:
    for i in xrange(1,100):
      acrt = acr + str(i)
      if not (acrt in acro):
        acro.append(acrt)
        acro2.append((acrt,ab[1]))
        name.append(ab[0])
        break


for x in xrange(len(acro)):
#  print "chr - " + acro[x] + " " + acro[x] + " 0 " + acro2[x][1] + " chr1" 
  print "chr\t-\t" + acro[x] + "\t" + name[x] + "\t0\t1\tblack"   