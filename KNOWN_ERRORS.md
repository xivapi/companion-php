# Known Error Codes

These are codes which I have seen from the Companion API and observed their behaviour and what causes them. Since there is no way to just obtain a list I am instead writing them down as I come across them.

## List

| Code     | Information                                                                                                                                       |
|----------|---------------------------------------------------------------------------------------------------------------------------------------------------|
| `100000` | Companion is down for maintenance, you will get 503 error code and the message "Under Maintenance"                                                |
| `111001` | Session has expired, the token will not work. Either it has been 24 hours since creation or it was not registered on the companion app correctly. |
| `111099` | You tried access a world you're currently not visiting.                                                                                           |
| `210010` | If you get this, you've been banned.                                                                                                              |
| `311004` | Don't know what causes this, maybe not logging into game for some time?                                                                           |
| `311006` | You have no characters.                                                                                                                           |
| `311007` | App says session expired, but it's because the cookie wasn't set properly (or at all) so you're technically not logged in                         |
| `311009` | likely not confirmed character status, happens if you do not callthat endpoint                                                                    |
| `319201` | Server being access has gone down for emergency maintenance                                                                                       |
| `340000` | Shit broke on SE's end. Seems generic.                                                                                                            |
