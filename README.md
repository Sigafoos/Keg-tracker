# Keg Tracker

By Dan Conley (dan.j.conley@gmail.com/[@Sigafoos](http://twitter.com/Sigafoos))

Originally developed for [Community Beer Works](http://www.communitybeerworks.com)

CBW needed a way to track kegs: what's where, what our current inventory is, etc. So I decided to write one.

It's based in jQuery Mobile/PHP/MySQL, because that's what I know. (well, I could also use Oracle, but we don't have that kind of money).

## Installing
1. Move config-sample.inc.php to config.inc.php
2. Edit your database information
3. Run install.php 
4. Remove install.php
5. There is no step 5

Well, that's not entirely true. The site will be open to the web, so you'll want to secure it somehow. I've been using .htpasswd (see Planned updates below).

## Planned updates
I'm considering this mostly feature-complete, primarily because I don't have a ton of time to add stuff and it basically does what we need it to. If you'd like to see something added I'm certainly open to it. Here's what I'd like to add when I have the time for it:

* Log in (via oauth/Facebook/something)
