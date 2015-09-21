#!/usr/bin/python



def get_prime(n):

    multiples = []
    for i in range(2,n+1):
        if i%2 != 0:
            if i not in multiples:
                print(i)
                for j in range(i*i, n+1, i):
                    multiples.append(j)

get_prime(100)


def get_primes(n):
  m = n+1
  numbers = [True for i in range(m)]
  for i in range(2, int(math.sqrt(n))):
    if numbers[i]:
      for j in range(i*i, m, i):
        numbers[j] = False
  primes = []
  for i in range(2, m):
    if numbers[i]:
      primes.append(i)
  return primes

start = time.time()
primes = get_primes(10000)
print(time.time() - start)
print(get_primes(100))


