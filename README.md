opensesame
=================

logs a user on then opens the course they are enrolled for.

passed in two possible parameters:

- data = the bin2hex() output of the encrypted version of the user id, which you're expected to know (e.g. you set up the user via a webservice)
- activity = the integer of the nth activity to open, defaults to 1 if not passed in

Usage:
------
You can not use this plugin directly; it is launched by an external custom php script.

Licence:
--------
GPL2, as per Moodle.

