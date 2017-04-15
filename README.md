opensesame
==========

logs a user on then opens the course they are enrolled for.

only takes a single parameter

- data = the bin2hex() output of the encrypted version of the user id, which you're expected to know (e.g. you set up the user via a webservice)

but inside the encrypted string is a querystring, which contains:
- auth_id = the mdl_users.id value of the user you want to log in as
- activity = the 1-based index of the activity you want to open, starting with the first one on section 0 and counting down.

Security
--------
The encryption uses *openssl* to do the work, and uses the `aes-256-cbc` cypher.

Installation:
-------------
Drop this in at `~/auth/opensesame` in your moodle installation and then (as admin) click on *site-admin->notifications* to install it.

Configuration:
--------------
You need to configure the **encryption** and **hmac keys**. You could just bash random keys for a few seconds, throwing in some numbers and symbols, maybe 50 to 100 charracters for each. Match these with whatever endpoint is encrypting the data of course. Or you could generate a value using something like `openssl rand -base64 32`

> Note! For production, remove the file at `~/auth/opensesame/classes/_test.php`!

Demo:
-----
look at the file in `~/auth/opensesame/classes/_test.php` - you'll have to edit it to put in its required parameters. You typically run this from another domain, but it will work internally too.

Usage:
------
You can not use this plugin directly; it is launched by an external custom php script.

Licence:
--------
GPL2, as per Moodle.

