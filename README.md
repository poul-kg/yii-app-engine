[PHP] Yii Framework application skeleton which can work with Google App Engine
===============================================================================

Explanation
------------

Here I'm creating Yii application skeleton which will work with Google App Engine.

### My goals: ###

* create git repo of Yii application skeleton which you can clone and upload
  to Google App Engine and it will instantly work
* add necessary components which will allow to use "Google Cloud SQL" and "Google Cloud Storage"
  instead of local MySQL and local filesystem
* Maybe something else, but that's enough to start

### What's done ###
* You can see the very first default Yii application page which you see if you execute ```yiic webpp myproject```
* Logs are routed to syslog (no need to have runtime directory writable)
