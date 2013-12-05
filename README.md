[PHP] Yii Framework application skeleton which can work with Google App Engine
===============================================================================

### Demo ###

[Live Demo](http://yii-framework.appspot.com/)

### Goals: ###

* create git repo of Yii application skeleton which you can clone and upload
  to Google App Engine and it will instantly work
* add necessary components which will allow to use "Google Cloud SQL" and "Google Cloud Storage"
  instead of local MySQL and local filesystem
* Maybe something else, but that's enough to start

### What's done ###
* You can see the very first default Yii application page which you see if you execute ```yiic webpp myproject```
* Logs are routed to syslog (no need to have runtime directory writable)
* Assets are published to Google Cloud Storage instead of local assets folder.

### TODO ###
* switch default page to /demo/default/index
* make cool looking bootstrap front page (with links to github)
* add Google Login button with icon
* logged in user will be able to change it's own details
* user data will be stored in Cloud SQL
* better documentation

### HOW-TO ###
1. ```cd your-project-dir```
2. ```git clone git@github.com:poul-kg/yii-app-engine.git```
3. [download latest yii framework](http://www.yiiframework.com/) 1.*.*, NOT 2.* and put it
   into ```your-project-dir/yii-framework``` directory, so that ```yii.php``` will have path
   like ```your-project-dir/yii-framework/yii.php```
4. Change ```webapp/protected/config/main.php``` file as you need.
5. If you need to publish assets, you will need to edit ```webapp/protected/config/main.php``` and add your
   own ```gs://``` like path.
6. Also you must give permissions to your app to write to your bucket, please read about it on
   [Google Cloud Storage Prerequisites page](https://developers.google.com/appengine/docs/python/googlestorage/index#Prerequisites)
   page
7. Push your app to Google App Engine
8. My IDE PHPStorm on Windows do this via the following cmd:
   ```cd your-project-dir```
   ```C:\Python27\python.exe "C:/Program Files (x86)/Google/google_appengine/appcfg.py" -e my@email.com --passin --no_cookies -R --runtime=php update .```
9. Go to */site/contact* page and check that jQuery and other JS files have been loaded. If not, you have problem with
   your asset directory configuration or your does not have proper permissions set.