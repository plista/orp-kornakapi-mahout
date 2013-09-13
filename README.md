Open Recommendation Platform - SDK
==================================
*Version 0.5*

The Open Recommendation Platform (ORP) is a distributed platform of entities capable of delivering recommendations for various purposes. It consists of recommendation providers and recommendation consumers that interact and communicate over a standardized protocol. This document describes the protocol and outlines the necessary steps a partner needs to take in order to integrate a technology as recommendation provider. The overall aim of the ORP is to obtain a better recommendation quality. In the context of advertising, better recommendations are defined by a higher CPM (cost per impression). In the context of on-site recommendations, better recommendations are defined by a higher CTR (click-through-rate). You will learn about these contexts in a later chapter. The chapter Push interface describes the API that recommendation providers need to implement.

Technical Restrictions
----------------------

Please ensure your system is able to reply within 100ms, as response time is critical for our application. Please further ensure that your system can handle the amount of incoming data. Expect up to several thousand requests per second. When we detect a performance problem, we may automatically decrease the amount of requests forwarded to your system.


State of Development
--------------------
The ORP and this protocol are actively being developed, so expect major changes of the API over time. This document is also still in an incomplete state. We will regularly publish updates.

For more informations about the ORP project please have a look at the http://orp.plista.com/




Requirements
------------
-  webserver (e.g. Apache)
-  PHP
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

`sudo apt-get install php5 libapache2-mod-php5`



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

**6. Install Maven 3 **
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
create kornakapi.conf
copy paste in kornakapi.conf:

&lt;configuration>

  &lt;modelDirectory>/path/to/model/&lt;/modelDirectory>
 &lt;numProcessorsForTraining>8&lt;/numProcessorsForTraining>

 &lt;storageConfiguration>
    &lt;jdbcDriverClass>com.mysql.jdbc.Driver&lt;/jdbcDriverClass>
    &lt;jdbcUrl>jdbc:mysql://localhost/kornakapi&lt;/jdbcUrl>
    &lt;username>dbuser&lt;/username>
    &lt;password>secret&lt;/password>
  &lt;/storageConfiguration>

  &lt;itembasedRecommenders>
    &lt;itembasedRecommender>
      &lt;name>itembased</name>
      &lt;similarityClass>org.apache.mahout.cf.taste.impl.similarity.LogLikelihoodSimilarity&lt;/similarityClass>
      &lt;similarItemsPerItem>25&lt;/similarItemsPerItem>
      &lt;retrainAfterPreferenceChanges>10000&lt;/retrainAfterPreferenceChanges>
      &lt;retrainCronExpression>0 0 1 * * ?&lt;/retrainCronExpression>
    &lt;/itembasedRecommender>
  &lt;/itembasedRecommenders>

  &lt;factorizationbasedRecommenders>
    &lt;factorizationbasedRecommender>
      &lt;name>weighted-mf</name>
      &lt;usesImplicitFeedback>true&lt;/usesImplicitFeedback>
      &lt;numberOfFeatures>4&lt;/numberOfFeatures>
      &lt;numberOfIterations>8&lt;/numberOfIterations>
      &lt;lambda>0.065&lt;/lambda>
      &lt;retrainAfterPreferenceChanges>2000&lt;/retrainAfterPreferenceChanges>
      &lt;retrainCronExpression>0 0 1 * * ?&lt;/retrainCronExpression>
    &lt;/factorizationbasedRecommender>
  &lt;/factorizationbasedRecommenders>

&lt;/configuration>\*

Be sure that you changed the path of you model directory in this line in kornakapi.conf <modelDirectory>/path/to/model/</modelDirectory>

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


