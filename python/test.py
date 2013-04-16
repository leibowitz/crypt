#!/usr/bin/env python

from hashlib import sha512
from pbkdf2 import PBKDF2
import hmac
import base64
import getpass
import os

domain = raw_input('Realm: ')
user = raw_input('User: ')
salt = raw_input('Salt: ')
password = getpass.getpass()

key = ':'.join([user, password, domain])
salt = salt if salt else base64.b64encode(os.urandom(32))
print salt
rounds = 1000
pwhash = PBKDF2(key, salt, rounds, macmodule=hmac, digestmodule=sha512)
print base64.b64encode(pwhash.read(64)).replace('+', '.')


