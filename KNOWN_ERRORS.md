# Known Error Codes

These are codes which I have seen from the Companion API and observed their behaviour and what causes them. Since there is no way to just obtain a list I am instead writing them down as I come across them.

## List

- **111001** = Session has expired, the token will not work. Either it has been 24 hours since creation or it was not registered on the companion app correctly.
- **340000** = Shit broke on SE's end. You will get this a lot if you spam their service (along with a permanent account Ban).
- **311004** = Don't know what causes this, maybe not logging into game for some time?
- **311007** = App says session expired, but it's because the cookie wasn't set properly (or at all) so you're technically not logged in
- **311009** = likely not confirmed character status, happens if you do not call that endpoint
