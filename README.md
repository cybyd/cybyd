# CyByD


> THiS branch iS for transition to MySQLi

**CyByD** is a PHP torrent tracker

works with 5.3 && greater :)

Just a few words about this piece of script and some credits ;)
The script is released under modified BSD, which mean you can freely use and
modify it (read LICENSE for more info)
xBTiT is a complete rewrite of our BtiTracker base code. Every file has been changed, it is impracticable to list all the changes here. xBTiT is the concentration of biteam.org's years of experience developing, hacking, and operating, tracker software. We are confident you are going to enjoy this release
To upgrade your modified Btit 1.4.x to xBTiT it is necessary to upgrade your current db using upgrade.php (not included in the standard package) and then reapply your hacks to the new xBTiT code. Although xBTiT has a hack template system designed to make the application of hacks easy, none of our 1.4.x hacks have yet been packaged for xBTiT, these will arrive in time as the community adopts the new code
xBTiT has two bittorrent tracker systems - a PHP tracker and xbtt. The PHP tracker is designed for platforms without access to the system root, or where your tracker is not expected to run with greater than 5-10,000 peers. A PHP tracker can generate a high volume of TCP traffic, potentially millions of hits per day on port 80, you have been cautioned. The second tracker system is xbtt by Olaf van der Spek. xbtt is an efficient C++ tracker capable of running millions of peers at very low overhead, you are recommended in all cases to use the xbtt system
The tracker is professionally supported for a small fee at http://www.xBTiT.com where you will also find private hacks, modifications, and styles
The opensource [free support forum](http://www.btiteam.org/index.php?action=forum)

# MAJoR FEATURES #

- real template system, 99% of the html code is out for the PHP files using [bTemplate](http://www.massassi.com/bTemplate/)
- rewritten (optimized) announce.php (the PHP tracker)
- integrated optional xbtt backend by Olaf Van der Spek http://xbtt.sourceforge.net/tracker/
- support for external mail server using [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- rewritten internal forum with subforum support
- integrated optional [SMF forum](http://www.simplemachines.org/) ( big thanks to petr1fied )
- one click hack installer, an easy way to install hacks into your tracker (a working example is provided)
- modules support
- new online procedure
- new AJAX shoutbox (big thanks to miskotes)
- XSS/SQL injection protection with log insertion (thank you cobracrk)
- new AJAX polls system (thank you to Ripper)
- new design (4 styles provided by TreepTopClimber)
- RSS reader (only class, with example in admincp for btiteam.org latest news)
- basic cache system
- new language system ( array is used instead of constant )
- smf_import script to import standard internal forum and users to smf ( thank you again to petr1fied )
- 1.4.x upgrade script
