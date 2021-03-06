Kornakapi Recommender for Open Recommendation Platform
==================================
*Version 0.5*

This is an example Recommender for ORP that uses Kornakapi.

Technical Restrictions
----------------------

This Recommender requires at least 16 gb ram and 8 cores to respond in time.




Requirements
------------
-  webserver (e.g. Apache)
-  PHP
- Maven
- Mahout
- Kornakapi
-  make sure your response time is less than 100ms

If you consider to participate with a server from outside of Germany, please get in touch with us:<br>
*contest (at) plista (dot) com* <br>
This is necessary due to the response time of the server

Install (in Ubuntu)
-------------------

**1. install webserver**


`sudo apt-get install apache2`

For further details you may want to have a look at http://wiki.ubuntuusers.de/Apache


**2. enable PHP support**

`sudo apt-get install php5 libapache2-mod-php5 php5-curl`



For further details you may want to have a look at http://php.net/manual/en/install.php

**3. getting Kornakapi Recommender from git**

 install git<br>
`sudo apt-get install git`

 cloning the git<br>
`git clone git://github.com/plista/orp-kornakapi-mahout.git`<br>
`cd orp-kornakapi-mahout`<br>
`git submodule init`<br>
`git submodule update`<br>

**4. getting data being written** <br>
'cd /var/www/'
creating directory <br>
`mkdir log` <br>
changing permissions <br>
`chmod 0777 log` <br>
`chown www-data:www-data log` <br>

**5. Install Maven 3**<br>
`sudo apt-get install maven`

**6. Install mahout 0.8**<br>
download mahout 0.8 from http://mahout.apache.org/<br>
`tar xvf mahout-distribution-0.8-src.tar.gz`<br>
`cd mahout-distribution-0.8-src`<br>
`mvn install`<br>

**7. Install MySQL and Setup your Database**<br>
`sudo apt-get install mysql-server`<br>
`mysql -u "username" -p "password"`<br>
`CREATE DATABASE kornakapi; USE kornakapi;`<br>

`CREATE TABLE taste_preferences (
  user_id bigint(20) NOT NULL,
  item_id bigint(20) NOT NULL,`<br>
  `preference float NOT NULL,
  PRIMARY KEY (user_id,item_id),
  KEY item_id (item_id)
);`
<br>
`CREATE TABLE taste_candidates (
  label varchar(255) NOT NULL,
  item_id bigint(20) NOT NULL,`<br>
  `PRIMARY KEY (label,item_id)
);`

**8. Get Kornakapi**<br>
`git clone https://github.com/plista/kornakapi.git kornakapi`

**9. Configure Kornakapi Recommender**<br>
`mkdir model`<br>
Edit kornakapi.conf in /path/to/orp-kornakapi-mahout/kornakapi.conf

Be sure to changed the path of your model directory in this line in kornakapi.conf &lt;modelDirectory&gt; /path/to/model/&lt; /modelDirectory&gt;
and under &lt;storageConfiguration&gt; adjust username and password for you mysql db.<br>
Also have a look at the number or cpu's, number of features, number of iterations, lambda in kornakapi.conf
You might be interessted in reading http://dl.acm.org/citation.cfm?id=1511352

**10. Link the index-orp.php**<br>
`cd /var/www/`<br>
`ln -s /path/to/your/index-orp.php`

**11. Start Kornakapi**<br>
`cd /path/to/your/kornakapi/`<br>
`mvn -Dkornakapi.conf=/path/to/kornakapi.conf tomcat:run`

**12. Sign up** <br>
Sign up at http://orp.plista.com <br>
Be sure to use the entire URL during the sign up process e.g.
`http://servername.domain/index-orp.php`

License
-------
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 3 of
the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.



*powered by Plista GmbH (http://plista.com/)* .


