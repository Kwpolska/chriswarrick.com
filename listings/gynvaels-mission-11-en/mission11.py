def check_password(s):
    good = '4e5d4e92865a4e495a86494b5a5d49525261865f5758534d4a89'.decode('hex')
    if len(s) != len(good):
        return False

    return all([ord(cs) - 89 & 255 ^ 115 ^ 50 == ord(cg) for cs, cg in zip(s, good)])
