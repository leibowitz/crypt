#!/usr/bin/env python

from hashlib import sha512
from pbkdf2 import PBKDF2
import hmac
import base64
import getpass

domain = raw_input('Domain: ')
login = raw_input('Login: ')
name = raw_input('Name: ')
password = getpass.getpass()

salt = 'abc'
rounds = 1000
print base64.b64encode(PBKDF2(password, salt, rounds, macmodule=hmac, digestmodule=sha512).read(64))

