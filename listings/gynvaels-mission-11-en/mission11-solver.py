#!/usr/bin/env python2
import dis
import sys

# Original function
def check_password(s):
    good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
    if len(s) != len(good):
        return False

    return all([ord(cs) - 89 & 255 ^ 115 ^ 50 == ord(cg) for cs, cg in zip(s, good)])

# Cracking the password
pass_values = {}
for i in range(256):
    result = i - 89 & 255 ^ 115 ^ 50
    pass_values[result] = i

good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
password = ''
for c in good:
    password += chr(pass_values[ord(c)])

print(password)
print(check_password(password))
# exit(0)

# Reverse engineering the code
cnames = ('co_argcount', 'co_consts', 'co_flags', 'co_name', 'co_names', 'co_nlocals', 'co_stacksize', 'co_varnames')
cvalues = (1, (None, '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89', 'hex', 89, 255, 115, 50), 67, 'check_password', ('decode', 'len', 'False', 'all', 'zip', 'ord'), 4, 6, ('s', 'good', 'cs', 'cg'))

for n, ov in zip(cnames, cvalues):
    v = getattr(check_password.__code__, n)
    if v == ov:
        sys.stderr.write('\033[1;32m')
    else:
        sys.stderr.write('\033[1;31m')
    sys.stderr.flush()
    sys.stdout.write(str(n) + " " + str(v) + "\n")
    sys.stdout.flush()

    sys.stderr.write('\033[0m')
    sys.stderr.flush()

dis.dis(check_password)
