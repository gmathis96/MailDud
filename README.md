# MailDud
{{{
This is a project that allows people to create anonymous email accounts. These email accounts can be thrown away.

To configure this project on a local server, you will need to know a little about MySQL, PHP, and Email Piping.
Step 1: Change the permission of /mailpipe.php to 755 (Gives the file executable permissions)
Step 2: Create a MySQL Database
Step 3: rename /core/configs.example.php to /core/configs.php
Step 4: Add Database credentials to configs.php
Step 5: Run the MySQL create table command below.
Step 6: Make sure your Apache server has mod rewrite installed and enabled
Step 7: enable the purge cron job, the url to the job would be http://yoursite.com/ajax/json/messagePurge/index

There's a couple of quirks to this project at the moment
Quirk 1: If you make any changes to /mailpipe.php, you need to reset the permissions
Quirk 2: This is using a framework built for another, much bigger system (A Social Network)
Quirk 3: Scalability, this system cannot scale very well at the moment -- horizontally of coarse.
}}}
#MySQL Create Table Command
```MySQL
CREATE TABLE IF NOT EXISTS messages (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(255) NOT NULL,
  `from` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  message text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```
