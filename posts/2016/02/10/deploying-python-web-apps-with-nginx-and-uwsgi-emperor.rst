.. title: Deploying Python Web Applications with nginx and uWSGI Emperor
.. slug: deploying-python-web-apps-with-nginx-and-uwsgi-emperor
.. date: 2016-02-10 15:00:00+01:00
.. updated: 2016-07-31 13:00:00+02:00
.. tags: Python, Django, Flask, uWSGI, nginx, Internet, Linux, Fedora, Arch Linux, Ubuntu, systemd
.. section: Python
.. description: A tutorial to deploy Python Web Applications to popular Linux systems.
.. type: text

You just wrote a great Python web application. Now, you want to share it with the world. In order to do that, you need a server, and some software to do that for you.

The following is a comprehensive guide on how to accomplish that, on multiple Linux-based operating systems, using nginx and uWSGI Emperor. It doesn’t force you to use any specific web framework — Flask, Django, Pyramid, Bottle will all work. Written for Ubuntu, Debian, Fedora, CentOS and Arch Linux (should be helpful for other systems, too)

*Revision 3 (2016-07-31): Ubuntu 16.04, Debian 8, Fedora 24, CentOS 7, Arch Linux*

.. TEASER_END

For easy linking, I set up some aliases: https://go.chriswarrick.com/pyweb and https://go.chriswarrick.com/uwsgi-tut (powered by a Django web application, deployed with nginx and uWSGI!)

Prerequisites
=============

In order to deploy your web application, you need a server that gives you root and ssh access — in other words, a VPS (or a dedicated server, or a datacenter lease…). If you’re looking for a great VPS service for a low price, I recommend `DigitalOcean`_ (reflink [#]_), which offers a $5/mo service [#]_. If you want to play along at home, without buying a VPS, you can create a virtual machine on your own, or use a Vagrant with a Vagrant box for Fedora 24 (``fedora/24-cloud-base``).

.. _DigitalOcean: https://www.digitalocean.com/?refcode=7983689b2ecc

Your server should also run a modern Linux-based operating system. This guide was written and tested on:

* Ubuntu 16.04 LTS
* Debian 8 (jessie)
* Fedora 24 (with SELinux enabled and disabled)
* CentOS 7 (with SELinux enabled and disabled)
* Arch Linux

Users of other Linux distributions (and perhaps other Unix flavors) can also follow this tutorial. This guide assumes ``systemd`` as your init system; if you are not using systemd, you will have to get your own daemon files somewhere else. In places where the instructions are split three-way, try coming up with your own, reading documentation and config files; the Arch Linux instructions are probably the closest to upstream (but not always).  Unfortunately, all Linux distributions have their own ideas when it comes to running and managing nginx and uWSGI.

nginx and uWSGI are considered best practices by most people. nginx is a fast, modern web server, with uWSGI support built in (without resorting to reverse proxying).  uWSGI is similarly aimed at speed.  The Emperor mode of uWSGI is recommended for init system integration by the uWSGI team, and it’s especially useful for multi-app deployments. (This guide is opinionated.)

.. note::

   All the commands in this tutorial are meant to be run **as root** — run ``su`` or ``sudo su`` first to get an administrative shell. This tutorial assumes familiarity with basic Linux administration and command-line usage.

Getting started
===============

Start by installing virtualenv, nginx and uWSGI. I recommend using your operating system packages. For uWSGI, we need the ``logfile`` and ``python3`` plugins. (Arch Linux names the ``python3`` plugin ``python``; the ``logfile`` plugin may be built-in — check with your system repositories!). I’ll also install Git to clone the tutorial app, but it’s optional if your workflow does not involve git.

**Ubuntu, Debian:**

.. code:: sh

   apt install virtualenv python3 uwsgi uwsgi-emperor uwsgi-plugin-python3 nginx-full git

**Fedora:**

.. code:: sh

   dnf install python3-virtualenv uwsgi uwsgi-plugin-python3 uwsgi-logger-file nginx git

**CentOS:**

.. code:: sh

   yum install epel-release
   yum install python34 uwsgi uwsgi-plugin-python3 uwsgi-logger-file nginx git wget
   wget https://bootstrap.pypa.io/get-pip.py
   python3 get-pip.py --user
   rm get-pip.py
   ~/.local/bin/pip install --user virtualenv

We need to install pip and virtualenv manually, because neither is packaged for CentOS, and the ``python-virtualenv`` package is not compatible. They will be available to root only (user install).

**Arch Linux:**

.. code:: sh

   pacman -S python-virtualenv uwsgi uwsgi-plugin-python nginx git

Preparing your application
==========================

This tutorial will work for any web framework. I will use `a really basic Flask app`_ that has just one route (``/``) [#]_, a static ``hello.png`` file and a ``favicon.ico`` for demonstration purposes. Note that the app does not use ``app.run()``. While you could add it, it would be used for local development and debugging only, and would have to be prepended by ``if __name__ == '__main__':`` (if it wasn’t, that server would run instead of uWSGI, which is bad)

.. _a really basic Flask app: https://github.com/Kwpolska/flask-demo-app

The app will be installed somewhere under the ``/srv`` directory, which is a great place to store things like this. I’ll choose ``/srv/myapp`` for this tutorial, but for real deployments, you should use sometihing more distinguishable — the domain name is a great idea.

If you don’t use Flask, this tutorial also has instructions for other web frameworks (Django, Pyramid, Bottle) in the configuration files; it should be adjustable to any other WSGI-compliant framework/script nevertheless.

We’ll start by creating a virtualenv:

**Ubuntu, Debian:**

.. code:: sh

   cd /srv
   virtualenv -p /usr/bin/python3 myapp

**Fedora:**

.. code:: sh

   cd /srv
   virtualenv-3.5 myapp

**CentOS:**

.. code:: sh

   cd /srv
   ~/.local/bin/virtualenv myapp

**Arch Linux:**

.. code:: sh

   cd /srv
   virtualenv3 myapp

(This tutorial assumes Python 3. Make sure you use the correct ``virtualenv`` command/argument. If you want to use Python 2.7, you’ll need to adjust your uWSGI configuration as well.)

Now, we need to put our app there and install requirements. An example for the tutorial demo app:

.. code:: sh

   cd myapp
   git clone https://github.com/Kwpolska/flask-demo-app appdata
   bin/pip install -r appdata/requirements.txt

I’m storing my application data in the ``appdata`` subdirectory so that it doesn’t clutter the virtualenv (or vice versa).  You may also install the ``uwsgi`` package in the virtualenv, but it’s optional.

What this directory should be depends on your web framework.  For example, for a Django app, you should have an ``appdata/manage.py`` file (in other words, ``appdata`` is where your app structure starts).  I also assumed that the ``appdata`` folder should have a ``static`` subdirectory with all static files, including ``favicon.ico`` if you have one (we will add support for both in nginx).

At this point, you should chown this directory to the user and group your server is going to run as.  This is especially important if uwsgi and nginx run as different users (as they do on Fedora). Run one of the following commands:

**Ubuntu, Debian:**

.. code:: sh

   chown -R www-data:www-data /srv/myapp

**Fedora, CentOS:**

.. code:: sh

   chown -R uwsgi:nginx /srv/myapp

**Arch Linux:**

.. code:: sh

   chown -R http:http /srv/myapp

Configuring uWSGI and nginx
===========================

.. note::

   Parts of the configuration depend on your operating system. I tried to provide advice for Ubuntu, Debian, Fedora, CentOS and Arch Linux. If you experience any issues, in particular with plugins, please consult the documentation.

We need to write a configuration file for uWSGI and nginx.

uWSGI configuration
-------------------

Start with this, but read the notes below and change the values accordingly:

.. code:: ini

   [uwsgi]
   socket = /srv/myapp/uwsgi.sock
   chmod-socket = 775
   chdir = /srv/myapp/appdata
   master = true
   binary-path = /srv/myapp/bin/uwsgi
   virtualenv = /srv/myapp
   module = flaskapp:app
   uid = www-data
   gid = www-data
   processes = 1
   threads = 1
   plugins = python3,logfile
   logger = file:/srv/myapp/uwsgi.log

Save this file as:

* Ubuntu, Debian: ``/etc/uwsgi-emperor/vassals/myapp.ini``
* Fedora, CentOS: ``/etc/uwsgi.d/myapp.ini``
* Arch Linux: ``/etc/uwsgi/vassals/myapp.ini`` (create the directory first and **chown** it to http: ``mkdir -p /etc/uwsgi/vassals; chown -R http:http /etc/uwsgi/vassals``)

The options are:

* ``socket`` — the socket file that will be used by your application. It’s usually a file path (Unix domain socket). You could use a local TCP socket, but it’s not recommended.
* ``chdir`` — the app directory.
* ``binary-path`` — the uWSGI executable to use. Remove if you didn’t install the (optional) ``uwsgi`` package in your virtualenv.
* ``virtualenv`` — the virtualenv for your application.
* ``module`` — the name of the module that houses your application, and the object that speaks the WSGI interface, separated by colons. This depends on your web framework:

  * For Flask: ``module = filename:app``, where ``filename`` is the name of your Python file (without the ``.py`` part) and ``app`` is the ``Flask`` object
  * For Django: ``module = project.wsgi:application``, where ``project`` is the name of your project (directory with ``settings.py``).  You should also add an environment variable: ``env = DJANGO_SETTINGS_MODULE=project.settings``
  * For Bottle: ``module = filename:app``, where ``app = bottle.default_app()``
  * For Pyramid: ``module = filename:app``, where ``app = config.make_wsgi_app()`` (make sure it’s **not** in a ``if __name__ == '__main__':`` block — the demo app does that!)

* ``uid`` and ``gid`` — the names of the user account to use for your server.  Use the same values as in the ``chown`` command above.
* ``processes`` and ``threads`` — control the resources devoted to this application. Because this is a simple hello app, I used one process with one thread, but for a real app, you will probably need more (you need to see what works the best; there is no algorithm to decide). Also, remember that if you use multiple processes, they don’t share memory (you need a database to share data between them).
* ``plugins`` — the list of uWSGI plugins to use. For Arch Linux, use ``plugins = python`` (the ``logfile`` plugin is always active).
* ``logger`` — the path to your app-specific logfile. (Other logging facilities are available, but this one is the easiest, especially for multiple applications on the same server)

You can test your configuration by running ``uwsgi --ini /path/to/myapp.ini`` (disable the logger for stderr output or run ``tail -f /srv/myapp/uwsgi.log`` in another window).

If you’re using **Fedora** or **CentOS**, there are two configuration changes you need to make globally: in ``/etc/uwsgi.ini``, disable the ``emperor-tyrant`` option (which seems to be buggy) and set ``gid = nginx``.  We’ll need this so that nginx can talk to your socket.

nginx configuration
-------------------

We need to configure our web server. Here’s a basic configuration that will get us started:

Save this file as:

* Ubuntu, Debian: ``/etc/nginx/sites-enabled/myapp.conf``
* Fedora, CentOS: ``/etc/nginx/conf.d/myapp.conf``
* Arch Linux: add ``include /etc/nginx/conf.d/*.conf;`` to your ``http`` directive in ``/etc/nginx/nginx.conf`` and use ``/etc/nginx/conf.d/myapp.conf``

.. code:: nginx

   server {
       # for a public HTTP server:
       listen 80;
       # for a public HTTPS server:
       # listen 443 ssl;
       server_name localhost myapp.local;

       location / {
           include uwsgi_params;
           uwsgi_pass unix:/srv/myapp/uwsgi.sock;
       }

       location /static {
           alias /srv/myapp/appdata/static;
       }

       location /favicon.ico {
           alias /srv/myapp/appdata/static/favicon.ico;
       }
   }

Note that this file is a very basic and rudimentary configuration. This configuration is fine for local testing, but for a real deployment, you will need to adjust it:

* set ``listen`` to ``443 ssl`` and create a http→https redirect on port 80 (you can get a free SSL certificate from `Let’s Encrypt`__; make sure to `configure SSL properly`__).
* set ``server_name`` to your real domain name
* you might also want to add custom error pages, log files, or change anything else that relates to your web server — consult other nginx guides for details
* nginx usually has some server already enabled by default — edit ``/etc/nginx/nginx.conf`` or remove their configuration files from your sites directory to disable it

__ https://letsencrypt.org/
__ https://raymii.org/s/tutorials/Strong_SSL_Security_On_nginx.html

Service setup
=============

After you’ve configured uWSGI and nginx, you need to enable and start the system services.

For Arch Linux
--------------

All you need is:

.. code:: sh

   systemctl enable nginx emperor.uwsgi
   systemctl start nginx emperor.uwsgi

Verify the service is running with ``systemctl status emperor.uwsgi``

For Fedora and CentOS
---------------------

Make sure you followed the extra note about editing ``/etc/uwsgi.ini`` earlier and run:

.. code:: sh

   systemctl enable nginx uwsgi
   systemctl start nginx uwsgi

Verify the service is running with ``systemctl status uwsgi``

If you disabled SELinux, this is enough to get an app working and you can skip over to the next section.

If you want to use SELinux, you need to do the following to allow nginx to read static files:

.. code:: sh

   setenforce 0
   chcon -R system_u:system_r:httpd_t:s0 /srv/myapp/appdata/static
   setenforce 1

We now need to install a `SELinux policy`_ (that I created for this project) to allow nginx and uWSGI to communicate.
Download it and run:

.. code:: sh

   semodule -i nginx-uwsgi.pp

Hopefully, this is enough (you can delete the file). In case it isn’t, please read SELinux documentation, check audit logs, and look into ``audit2allow``.

.. _SELinux policy: https://chriswarrick.com/pub/nginx-uwsgi.pp

For Ubuntu and Debian
---------------------

Ubuntu and Debian (still!) use LSB services for uWSGI. Because LSB services are awful, we’re going to set up our own systemd-based (native) service.

Start by disabling the LSB service that comes with Ubuntu and Debian:

.. code:: sh

   systemctl stop uwsgi-emperor
   systemctl disable uwsgi-emperor

Copy the ``.service`` file from the `uWSGI systemd documentation`_ to ``/etc/systemd/system/emperor.uwsgi.service``.  Change the ExecStart line to:

.. code:: ini

   ExecStart=/usr/bin/uwsgi --ini /etc/uwsgi-emperor/emperor.ini

You can now reload systemd daemons and enable the services:

.. code:: sh

   systemctl daemon-reload
   systemctl enable nginx emperor.uwsgi
   systemctl reload nginx
   systemctl start emperor.uwsgi

Verify the service is running with ``systemctl status emperor.uwsgi``.  (Ignore
the warning about no request plugin)

.. _uWSGI systemd documentation: https://uwsgi-docs.readthedocs.org/en/latest/Systemd.html#adding-the-emperor-to-systemd

End result
==========

Your web service should now be running at http://localhost/ (or wherever you set up server to listen).

If you used the demo application, you should see something like this (complete with the favicon and image greeting):

.. image:: /images/nginx-uwsgi-demo.png
   :class: centered

If you want to test with cURL:

.. code:: sh

   curl -v http://localhost/
   curl -I http://localhost/favicon.ico
   curl -I http://localhost/static/hello.png

Troubleshooting
---------------

Hopefully, everything works. If it doesn’t:

* Check your nginx, system (``journalctl``, ``systemctl status SERVICE``) and uwsgi (``/srv/myapp/uwsgi.log``) logs.
* Make sure you followed all instructions.
* If you have a firewall installed, make sure to open the ports your web server runs on (typically 80/443). For ``firewalld`` (Fedora, CentOS):

.. code:: sh

   firewall-cmd --add-service http
   firewall-cmd --add-service https

.. [#] This reflink gives you $10 in credit, which is enough to run a server for up to two months without paying a thing. I earn $15.
.. [#] For the cheapest plan. If you’re in the EU (and thus have to pay VAT), or want DO to handle your backups, it will cost you a little more.
.. [#] This app does not use templates, but you should in any real project. This app is meant to be as simple as possible.
