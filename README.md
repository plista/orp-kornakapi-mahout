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
- php5-curl
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

**3. getting Sdk from git**

 install git<br>
`sudo apt-get install git`

 make sure to be in the right place<br>
`ch /var/www/`

 cloning the git<br>
`git clone git://github.com/plista/orp-kornakapi-mahout.git`

For further details you may want to have a look at http://githowto.com/

**4. getting Sdk ready** <br>
Have a look at the example provided in `classes/Plista/Orp/Sdk/Example`

**5. getting data being written** <br>
creating directory <br>
`mkdir logs` <br>
changing permissions <br>
`chmod 0777 logs` <br>
`chown www-data:www-data logs` <br>

**6. Install Maven 3**
`sudo apt-get install maven`

**7. Install mahout 0.8 **
download mahout o.8 from http://mahout.apache.org/
`tar xvf mahout-distribution-0.8-src.tar.gz`
`cd mahout-distribution-0.8-src`
`mvn install`

**8. Install MySQL and Setup your Database**
`sudo apt-get install mysql-server`
`mysql -u "username" -p "password"`
`CREATE DATABASE kornakapi;
USE kornakapi;`

`CREATE TABLE taste_preferences (
  user_id bigint(20) NOT NULL,
  item_id bigint(20) NOT NULL,
  preference float NOT NULL,
  PRIMARY KEY (user_id,item_id),
  KEY item_id (item_id)
);`

`CREATE TABLE taste_candidates (
  label varchar(255) NOT NULL,
  item_id bigint(20) NOT NULL,
  PRIMARY KEY (label,item_id)
);`

**9. Get Kornakapi**
`git clone https://github.com/plista/kornakapi.git kornakapi`

**10. Configure Kornakapi Recommender**
`mkdir model`
copy paste kornakapi.conf to /path/to/kornakapi.conf:

Be sure that you changed the path of you model directory in this line in kornakapi.conf &lt;modelDirectory&gt; /path/to/model/&lt; /modelDirectory&gt;

**11. Start Kornakapi
`cd /path/to/your/kornakapi/`
`mvn -Dkornakapi.conf=/path/to/kornakapi.conf tomcat:run`


**12. Sign up** <br>
Sign up at http://orp.plista.com <br>
Be sure to use the entire URL during the sign up process e.g.
`http://servername.domain/index-sdk-example.php`

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


