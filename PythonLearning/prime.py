def primes(n):

    ps, sieve = [], [True] * (n + 1)
    for p in range(2, n + 1):
        if sieve[p]:
            ps.append(p)
            for i in range(p * p, n+1, p):
                sieve[i] = False
    return ps


prms = primes(100)

print prms
