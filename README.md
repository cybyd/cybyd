# CyByD


> THiS branch is for transition to MySQLi

**CyByD** is a php torrent tracker


Just a few words about this piece of script and some credits ;)
The script is released under modified BSD, which mean you can freely use and
modify it (read LICENSE for more info)
xbtit is a complete rewrite of our BtiTracker base code. Every file has been changed, it is impracticable to list all the changes here. xbtit is the concentration of biteam.org's years of experience developing, hacking, and operating, tracker software. We are confident you are going to enjoy this release
To upgrade your modified Btit 1.4.x to xbtit it is necessary to upgrade your current db using upgrade.php (not included in the standard package) and then reapply your hacks to the new xbtit code. Although xbtit has a hack template system designed to make the application of hacks easy, none of our 1.4.x hacks have yet been packaged for xbtit, these will arrive in time as the community adopts the new code
xbtit has two bittorrent tracker systems - a PHP tracker and xbtt. The PHP tracker is designed for platforms without access to the system root, or where your tracker is not expected to run with greater than 5-10,000 peers. A PHP tracker can generate a high volume of TCP traffic, potentially millions of hits per day on port 80, you have been cautioned. The second tracker system is xbtt by Olaf van der Spek. xbtt is an efficient C++ tracker capable of running millions of peers at very low overhead, you are recommended in all cases to use the xbtt system
The tracker is professionally supported for a small fee at http://www.xbtit.com where you will also find private hacks, modifications, and styles
The opensource free support forum is http://www.btiteam.org

# MAJOR FEATURES #

- real template system, 99% of the html code is out for the PHP files using bTemplate http://www.massassi.com/bTemplate/
- rewritten (optimized) announce.php (the PHP tracker)
- integrated optional xbtt backend by Olaf Van der Spek http://xbtt.sourceforge.net/tracker/
- support for external mail server using phpmailer http://sourceforge.net/projects/phpmailer
- rewritten internal forum with subforum support
- integrated optional smf forum (big thanks to petr1fied) http://www.simplemachines.org/
- one click hack installer, an easy way to install hacks into your tracker (a working example is provided)
- modules support
- new online procedure
- new AJAX shoutbox (big thanks to miskotes)
- XSS/SQL injection protection with log insertion (thank you cobracrk)
- new AJAX polls system (thank you to Ripper)
- new design (4 styles provided by TreepTopClimber)
- RSS reader (only class, with example in admincp for btiteam.org latest news)
- basic cache system
- new language system (array is used instead of constant)
- smf_import script to import standard internal forum and users to smf (thank you again to petr1fied)
- 1.4.x upgrade script
