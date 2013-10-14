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
