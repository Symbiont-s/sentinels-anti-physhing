### sentinels-anti-physhing

sentinels-anti-physhing is a project that was created to deal with phishing, farming, cybercrime, and abuse cases on the Steem Blockchain.

The project will allow you to:

* Create and categorize the types of abuse;
* use a custom message as a warning to users by posting it under their posts;
* track malicious memo send to users;
* automatically blacklist users that fall within x set of rules;
* use specific on-chain commands to add users to the blacklist;
* create teams and managers;
* accept users reports and review them;
* and Use the dashboard which provides a compressive set of features to manage everything and more!   

The project is currently discontinued  due to a  lack of support and resources, until further notice. It is advisable for whoever is going to run this project, to have enough resources to at least have a real impact on the chain.

### Requeriments

* PHP 7.0 or above;
* cronjob and crontab usage;
* nodejs 10.19.0 or above for bots;
* node-mysql, moment, node-fetch, and steem dependencies;
* and pm2.

### First Steps


1- Go to PHPMyAdmin, create a new database called ```phishingtoolDB``` and Import the SQL file.

2- Go to ```controller/config.php``` and set your database connection.

3- Go to ```bot/db.js``` to set your database connection.    
   
   Edit the ```this.data``` property for DB class.

5- Go to ```public/templates/libraries.php``` and edit the ```<base>``` HTML tag, you must add your hostname.

   ```<base href="https://yourdomain.com/">```
   
6- Go to ```controller/cronjob.php``` and set your domain and path to execute the following accounts script.

   ```curl_setopt($client, CURLOPT_URL, "https://yourdomain.com/update/phishers")```   

### Bots Dependencies

1- After cloning  this repo move to the bot folder:

```cd bot```

2- Install dependencies by executing:

```npm i```

### Cronjobs Installation

1- On your bash execute:

```crontab -e```

2- Edit the crontab file and add your cronjobs.

The project uses one cronjob to check every x minute the list of friends to update the blacklist if the features is enabled on the dashboard:

```*/30 * * * * php /var/www/html/controller/cronjob.php```

### Bots Installation

A node bot is used to detect the sentinels actions, phishers activities, and phishing links usage on the Steem Blockchain.

1- Start the bots:

```
pm2 start /var/www/html/bot/detector.js --name friends

pm2 start /var/www/html/bot/links.js --name links

pm2 start /var/www/html/bot/phishers.js --name phishers
```

Then

```pm2 save```
```pm2 startup```

2- You will need to set a cronjob to auto restart the bot every x minutes to deal with potential issues.

The following lines will restarts your bots every 5 minutes, you can set the time what you need:

``` 
pm2 restart links --cron "*/5 * * * *"

pm2 restart phishers --cron "*/5 * * * *"

pm2 restart friends --cron "*/5 * * * *"
```

### Additional Notes

If you have disabled the use of .htaccess files on your dedicated host please follow these steps.

* To enable htaccess (e.g Apache) on your host, you must edit apache2.conf file at ```/etc/apache2/```.

Find:

```
<Directory /var/www/>
	Options Indexes FollowSymLinks
	AllowOverride None
	Require all granted
</Directory>
```

and change 'AllowOverride' to 'All'.

* Next step will be to enable rewrite module by executing:

```sudo a2enmod rewrite```

To apply changes do:

```sudo service apache2 restart``` 

* Change the permissions for the checker folder to avoid a data leak, do:

```chmod 770 /var/www/html/checker/```

### License

GNU GENERAL PUBLIC LICENSE Version 3.

Brought to you by the Symbionts Team.
