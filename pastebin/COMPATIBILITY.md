The KwPastebin is changing.  With each modification some backwards
compatibility is lost. This is a list of them with an explaination.  The
database upgrades can be performed with help of an approperiate file in the
/upgrade/ directory.

Major updates list
==================

This is a ``changelog" for the project.  Only the important modifications that
modify the database/behavior/etc are listed.  The dates are in mm/dd/yy format.

    +-----------+-----------+-------------------+---------------------------+
    | date      | type      | reason            | changes                   |
    +-----------+-----------+-------------------+---------------------------+
    | 06/12/11  | db, cfg   | SQLite support    | config, all php files     |
    | 03/19/11  | db, bhvr  | P rem., db ch.    | +rmable, +rmid, +del.php  |
    | 03/13/11  | db        | New unique IDs    | +pasteid, mod. timestamp  |
    +-----------+-------- --+-------------------+---------------------------+

Please read the following explainations and (if needed) execute the
approperiate file(s) from the /upgrade/ directory.

Explainations
=============

This is an explaination of all the updates made to the project (newest comes
first).  All the dates are in mm/dd/yy format.

06/12/11
--------

          Date: June 12th, 2011  
          Type: Database and config modification
        Reason: Adding SQLite support
       Changes: All own PHP files (incl. config) changed

SQLite support was added this time.  Because of that, the following changes
were made:

 * new connection routine
 * modified config file

03/19/11
--------

          Date: March 19th, 2011  
          Type: Database and behavior modification  
        Reason: Removal features, DB reogranization  
       Changes: Added the file del.php, db changes described below

The update is really big.  The first new feature is removal.  Two new fields
was added to protect abuse.  They are ``rmable" and ``rmid".  The ``rmable"
field is a TINYINT(1).  If it's set to 1, the script displays the contents of
the ``rmid" field.  The ``rmid" field is a VARCHAR(100).  If you give it to the
``del.php" script, you'll be able to remove a paste.  The ``timestamp" field
became an INT.  The ``pasteid" field became a primary key.  The database table
structure was reorganized (you don't have to change it in your database).
Right now, it looks like this:

    +-----------+--------------+------+-----+---------+-------+
    | Field     | Type         | Null | Key | Default | Extra |
    +-----------+--------------+------+-----+---------+-------+
    | pasteid   | varchar(100) | NO   | PRI | NULL    |       |
    | timestamp | int(50)      | NO   |     | NULL    |       |
    | language  | varchar(50)  | NO   |     | NULL    |       |
    | dsc       | varchar(250) | YES  |     | NULL    |       |
    | rmable    | tinyint(1)   | NO   |     | NULL    |       |
    | rmid      | varchar(100) | NO   |     | NULL    |       |
    | code      | text         | NO   |     | NULL    |       |
    +-----------+--------------+------+-----+---------+-------+

Another new feature are the upgrade files.  You can use them to change the database structure.  These files will (and are) be offered for all the database updates.


03/13/11
--------

       Date: March 13th, 2011  
       Type: Database modification  
     Reason: New unique IDs.  
    Changes: Added the field pasteid, modified the timestamp field.

This modification replaced relying on the current timestamp and a random number
as the entry ID. The function used now is uniqid().  The ID is *always* unique.
See the new database table:

    +-----------+--------------+------+-----+---------+-------+
    | Field     | Type         | Null | Key | Default | Extra |
    +-----------+--------------+------+-----+---------+-------+
    | code      | text         | NO   |     | NULL    |       |
    | language  | varchar(50)  | NO   |     | NULL    |       |
    | pasteid   | varchar(100) | NO   |     | NULL    |       |
    | timestamp | varchar(50)  | NO   |     | NULL    |       |
    | dsc       | varchar(250) | YES  |     | NULL    |       |
    +-----------+--------------+------+-----+---------+-------+

A new field, pasteid, varchar(100), NOT NULL was added. See this:

    +-------------+----------+--------------+-------------+
    | code        | language | timestamp    | dsc         |
    +-------------+----------+--------------+-------------+
    | Go to: hell | text     | 1299956895.3 | Go to: hell |
    +-------------+----------+--------------+-------------+

    +-------------+----------+---------------+------------+-------------+
    | code        | language | pasteid       | timestamp  | dsc         |
    +-------------+----------+---------------+------------+-------------+
    | Go to: hell | text     | 4d7c94caeb336 | 1299956895 | Go to: hell |
    +-------------+----------+---------------+------------+-------------+

The timestamp field no longer has the random number.  The pasteid field is
being generated using uniqueid().  You have to change them.  Using the old
timestamp.number form is fine with the script if you'll use it in the
``pasteid" field.  The ``timestamp" field *must* be changed if you want to use
the new version.

This document is Copyright (C) Kwpolska 2011 and a part of KwPastebin.  The
newest version is always available at <https://github.com/Kwpolska/kwpastebin>.
