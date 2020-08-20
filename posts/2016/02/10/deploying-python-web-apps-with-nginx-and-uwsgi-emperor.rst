.. title: Deploying Python Web Applications with nginx and uWSGI Emperor
.. slug: deploying-python-web-apps-with-nginx-and-uwsgi-emperor
.. date: 2016-02-10 15:00:00+01:00
.. tags: Python, Django, Flask, uWSGI, nginx, Internet, Linux, Arch Linux, systemd, Ansible, guide
.. category: Python
.. description: A tutorial to deploy Python Web Applications to popular Linux systems.
.. type: text
.. guide: yes
.. guide_effect: your Python web app is up and running
.. guide_platform: Ubuntu, Debian, Fedora, CentOS, Arch Linux
.. guide_topic: Python, web apps
.. shortlink: pyweb
.. updated: 2020-05-14 14:00:00+02:00

You’ve just written a great Python web application. Now, you want to share it with the world. In order to do that, you need a server, and some software to do that for you.

The following is a comprehensive guide on how to accomplish that, on multiple Linux-based operating systems, using nginx and uWSGI Emperor. It doesn’t force you to use any specific web framework — Flask, Django, Pyramid, Bottle will all work. Written for Ubuntu, Debian, Fedora, CentOS 7 and Arch Linux (should be helpful for other systems, too). Now with an Ansible Playbook.

*Revision 7c (2020-05-01): works with Ubuntu 20.04 and Fedora 32; previous Revision 7a (2020-02-03): Move virtual environment to separate venv folder to improve Python upgrades (venvs should be ephemeral); add Docker section*

.. TEASER_END

.. |ci-status| image:: https://github.com/Kwpolska/ansible-nginx-uwsgi/workflows/CI%20in%20Docker%20for%20ansible-nginx-uwsgi%20%28pyweb%29/badge.svg

CI status for the associated Ansible Playbook: |ci-status|

For easy linking, I set up some aliases: https://go.chriswarrick.com/pyweb and https://go.chriswarrick.com/uwsgi-tut (powered by a Django web application, deployed with nginx and uWSGI!).

.. class:: right-toc

.. contents::

Prerequisites
~~~~~~~~~~~~~

In order to deploy your web application, you need a server that gives you root and ssh access — in other words, a VPS (or a dedicated server, or a datacenter lease…). If you’re looking for a great VPS service for a low price, I recommend `Hetzner Cloud`_ (reflink [#]_), which offers a pretty good entry-level VPS for €2.49 + VAT / month (with higher plans available for equally good prices). If you want to play along at home, without buying a VPS, you can create a virtual machine on your own, or use Vagrant with a Vagrant box for Fedora 32 (``fedora/32-cloud-base``).

.. _Hetzner Cloud: https://hetzner.cloud/?ref=Qy1lehF8PwzP

Your server should also run a modern Linux-based operating system. This guide was written and tested on:

* Ubuntu 16.04 LTS, 18.04 LTS, 20.04 LTS or newer
* Debian 9 (stretch), 10 (buster) or newer
* Fedora 29-32 or newer (with SELinux enabled and disabled)
* CentOS 7 (with SELinux enabled and disabled) — manual guide should also work on RHEL 7. CentOS 8 does not have uWSGI packages in EPEL as of May 2020, but they should become available soon.
* Arch Linux

Debian 8 (jessie), and Fedora 24 through 28 are not officially supported, even though they still probably work.

What if you’re using **Docker**? The story is a bit complicated, and this guide does not apply, but do check the `Can I use Docker?`_ at the end of this post for some hints on how to approach it.

Users of other Linux distributions (and perhaps other Unix flavors) can also follow this tutorial. This guide assumes ``systemd`` as your init system; if you are not using systemd, you will have to get your own daemon files somewhere else. In places where the instructions are split three-way, try coming up with your own, reading documentation and config files; the Arch Linux instructions are probably the closest to upstream (but not always).  Unfortunately, all Linux distributions have their own ideas when it comes to running and managing nginx and uWSGI.

nginx and uWSGI are considered best practices by most people. nginx is a fast, modern web server, with uWSGI support built in (without resorting to reverse proxying).  uWSGI is similarly aimed at speed.  The Emperor mode of uWSGI is recommended for init system integration by the uWSGI team, and it’s especially useful for multi-app deployments. (This guide is opinionated.)

Automate everything: Ansible Playbook
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. class:: lead

A Playbook_ that automates everything in this tutorial is available. |ci-status|

How to use
==========

1. Install Ansible_ on your control computer (not necessarily the destination server).
2. Clone the Playbook_ from GitHub.
3. Read ``README.md``. You should also understand how Ansible works.
4. Configure (change three files: ``hosts``, ``group_vars/all``, and ``group_vars/os_<destination OS>``
5. Make sure all the dependencies are installed on your destination server
6. Run ``ansible-playbook -v nginx-uwsgi.yml -i hosts`` and watch magic happen.
7. Skip over to `End result`_ and test your site.

.. _Ansible: https://docs.ansible.com/ansible/intro_installation.html
.. _Playbook: https://github.com/Kwpolska/ansible-nginx-uwsgi

The manual guide
~~~~~~~~~~~~~~~~

Even though I personally recommend the Playbook as a much less error-prone way to set up your app, it might not be compatible with everyone’s system, or otherwise be the wrong solution. The original manual configuration guide is still maintained.

Even if you are using the Playbook, you should still read this to find out what happens under the hood, and to find out about other caveats/required configuration changes.

.. note::

   All the commands in this tutorial are meant to be run **as root** — run ``su`` or ``sudo su`` first to get an administrative shell. This tutorial assumes familiarity with basic Linux administration and command-line usage.


Getting started
===============

Start by installing Python 3 (with venv), nginx and uWSGI. I recommend using your operating system’s packages. Make sure you are downloading the latest versions available for your OS (update the package cache first). For uWSGI, we need the ``logfile`` and ``python3`` plugins. (Arch Linux names the ``python3`` plugin ``python``; the ``logfile`` plugin may be built-in — check with your system repositories!). I’ll also install Git to clone the tutorial app, but it’s optional if your workflow does not involve Git.

**Ubuntu, Debian:**

.. code:: sh

   apt install python3 python3-venv uwsgi uwsgi-emperor uwsgi-plugin-python3 nginx-full git

**Fedora:**

.. code:: sh

   dnf install python3 uwsgi uwsgi-plugin-python3 uwsgi-logger-file nginx git

**CentOS 7:**

.. code:: sh

   yum install epel-release
   yum install python36 uwsgi uwsgi-plugin-python36 uwsgi-logger-file nginx git wget

**Arch Linux:**

.. code:: sh

   pacman -S python uwsgi uwsgi-plugin-python nginx git

Preparing your application
==========================

This tutorial will work for any web framework. I will use `a really basic Flask app`_ that has just one route (``/``), a static ``hello.png`` file and a ``favicon.ico`` for demonstration purposes. The app is pretty basic, but all the usual advanced features (templates, user logins, database access, etc.) would work without any other web server-related config. Note that the app does not use ``app.run()``. While you could add it, it would be used for local development and debugging only, and would have to be prepended by ``if __name__ == '__main__':`` (if it wasn’t, that server would run instead of uWSGI, which is bad)

.. _a really basic Flask app: https://github.com/Kwpolska/flask-demo-app

The app will be installed somewhere under the ``/srv`` directory, which is a great place to store things like this. I’ll choose ``/srv/myapp`` for this tutorial, but for real deployments, you should use something more distinguishable — the domain name is a great idea.

If you don’t use Flask, this tutorial also has instructions for other web frameworks (Django, Pyramid, Bottle) in the configuration files; it should be adjustable to any other WSGI-compliant framework/script nevertheless.

.. sidebar:: Paths and locations

    This guide used to recommend creating the venv in ``/srv/myapp``. This was changed to improve in-place Python upgrades. Virtual environments should be ephemeral, so that ``rm -rf $VIRTUAL_ENV`` is recoverable in less than 10 minutes and 2 commands. The old structure made the venv hard to delete without deleting ``appdata``. The current structure has ``/srv/myapp/venv`` and ``/srv/myapp/appdata`` separate. An alternative structure would put the app in ``/srv/myapp``, but that requires including ``venv``, sockets and other deployment-specific files in ``.gitignore`` (or having dirty working directories).

We’ll start by creating a virtual environment, which is very easy with Python 3:

.. code:: sh

   mkdir /srv/myapp
   python3 -m venv --prompt myapp /srv/myapp/venv

(The ``--prompt`` option is not supported on some old versions of Python, but you can just skip it if that’s the case, it’s just to make the prompt after ``source bin/activate`` more informative.)

Now, we need to put our app there and install requirements. An example for the tutorial demo app:

.. code:: sh

   cd /srv/myapp
   git clone https://github.com/Kwpolska/flask-demo-app appdata
   venv/bin/pip install -r appdata/requirements.txt

I’m storing my application data in the ``appdata`` subdirectory so that it doesn’t clutter the virtual environment (or vice versa).  You may also install the ``uwsgi`` package in the virtual environment, but it’s optional.

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
   :linenos:

   [uwsgi]
   socket = /srv/myapp/uwsgi.sock
   chmod-socket = 775
   chdir = /srv/myapp/appdata
   master = true
   binary-path = /srv/myapp/venv/bin/uwsgi
   virtualenv = /srv/myapp/venv
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
* ``binary-path`` — the uWSGI executable to use. Remove if you didn’t install the (optional) ``uwsgi`` package in your virtual environment.
* ``virtualenv`` — the virtual environment for your application.
* ``module`` — the name of the module that houses your application, and the object that speaks the WSGI interface, separated by colons. This depends on your web framework:

  .. raw:: html

    <div class="table-responsive-lg">
    <table class="table table-bordered">
    <thead><tr>
    <th style="width: 10%">Framework</th>
    <th style="width: 30%">Flask, Bottle</th>
    <th style="width: 30%">Django</th>
    <th style="width: 30%">Pyramid</th>
    </tr></thead>
    <tbody>
    <tr>
    <th>Package</th>
    <td>module where <code>app</code> is defined</td>
    <td><code><em>project</em>.wsgi</code><br><span style="font-size: 0.9rem">(<code style="font-size: 0.9rem"><em>project</em></code> is the package with <code style="font-size: 0.9rem">settings.py</code>)</span></td>
    <td>module where <code>app</code> is defined</td>
    </tr>
    <tr>
    <th>Callable</th>
    <td>Flask: <code>app</code> instance<br>Bottle: <code>app = bottle.default_app()</code></td>
    <td><code>application</code></td>
    <td><code>app = config.make_wsgi_app()</code></td>
    </tr>
    <tr class="table-active">
    <th>Module</th>
    <td><code style="font-size: 1.2rem"><em>package</em>:app</code></td>
    <td><code style="font-size: 1.2rem"><em>project</em>.wsgi:application</code></td>
    <td><code style="font-size: 1.2rem"><em>package</em>:app</code></td>
    </tr>
    <tr>
    <th>Caveats</th>
    <td>Make sure <code>app</code> is <strong>not</strong> in an <code style="font-size: 0.85rem">if __name__ == '__main__':</code> block</td>
    <td>Add environment variable for settings:<br><code style="font-size: 0.7rem">env = DJANGO_SETTINGS_MODULE=<em>project</em>.settings</code></td>
    <td>Make sure <code>app</code> is <strong>not</strong> in an <code style="font-size: 0.85rem">if __name__ == '__main__':</code> block (the demo quickstart does that!)</td>
    </tr>
    </tbody>
    </table>
    </div>

* ``uid`` and ``gid`` — the names of the user account to use for your server.  Use the same values as in the ``chown`` command above.
* ``processes`` and ``threads`` — control the resources devoted to this application. Because this is a simple hello app, I used one process with one thread, but for a real app, you will probably need more (you need to see what works the best; there is no algorithm to decide). Also, remember that if you use multiple processes, they don’t share memory (you need a database to share data between them).
* ``plugins`` — the list of uWSGI plugins to use. For Arch Linux, use ``plugins = python`` (the ``logfile`` plugin is always active).  For CentOS, use ``plugins = python36``.
* ``logger`` — the path to your app-specific logfile. (Other logging facilities are available, but this one is the easiest, especially for multiple applications on the same server)
* ``env`` — environment variables to pass to your app. Useful for configuration, may be specified multiple times. Example for Django: ``env = DJANGO_SETTINGS_MODULE=project.settings``

You can test your configuration by running ``uwsgi --ini /path/to/myapp.ini`` (disable the logger for stderr output or run ``tail -f /srv/myapp/uwsgi.log`` in another window).

If you’re using **Fedora** or **CentOS**, there are two configuration changes you need to make globally: in ``/etc/uwsgi.ini``, disable the ``emperor-tyrant`` option (which we don’t need, as it sets uid/gid for every process based on the owner of the related ``.ini`` config file — we use one global setup) and set ``gid = nginx``.  We’ll need this so that nginx can talk to your socket.

nginx configuration
-------------------

We need to configure our web server. Here’s a basic configuration that will get us started:

Save this file as:

* Ubuntu, Debian: ``/etc/nginx/sites-enabled/myapp.conf``
* Fedora, CentOS: ``/etc/nginx/conf.d/myapp.conf``
* Arch Linux: add ``include /etc/nginx/conf.d/*.conf;`` to your ``http`` directive in ``/etc/nginx/nginx.conf`` and use ``/etc/nginx/conf.d/myapp.conf``

.. code:: nginx
   :linenos:

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

We now need to install a `SELinux policy`_ (that I created for this project; updated 2020-05-02) to allow nginx and uWSGI to communicate.
Download `nginx-uwsgi.pp`_ and run:

.. code:: sh

   semodule -i nginx-uwsgi.pp

Hopefully, this is enough (you can delete the file). In case it isn’t, please read SELinux documentation, check audit logs, and look into ``audit2allow``.

.. _SELinux policy: https://chriswarrick.com/pub/nginx-uwsgi.pp
.. _nginx-uwsgi.pp: https://chriswarrick.com/pub/nginx-uwsgi.pp

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
~~~~~~~~~~

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
===============

Hopefully, everything works. If it doesn’t:

* Check your nginx, system (``journalctl``, ``systemctl status SERVICE``) and uwsgi (``/srv/myapp/uwsgi.log``) logs.
* Make sure you followed all instructions.
* If you get a default site, disable that site in nginx config (``/etc/nginx/nginx.conf`` or your sites directory).
* If you have a firewall installed, make sure to open the ports your web server runs on (typically 80/443). For ``firewalld`` (Fedora, CentOS):

.. code:: sh

   firewall-cmd --add-service http
   firewall-cmd --add-service https

* If it still does not work, feel free to ask in the comments, mentioning your distribution, installation method, and what doesn’t work.


Can I use Docker?
~~~~~~~~~~~~~~~~~

This blog post is written for systems running standalone. But Docker is a bit special, in that it offers a limited subset of OS features this workflow expects. The main issue is with user accounts, which generally work weird in Docker, and I had issues with ``setuid``/``setgid`` as used by uWSGI. Another issue is the lack of systemd, which means that another part of the tutorial fails to apply.

This tutorial uses uWSGI Emperor, which can run multiple sites at once, and offers other management features (such as seamless code restarts with ``touch /etc/uwsgi/vassals/myapp.ini``) that may not be useful or easy to use in a Docker environment. You’d probably also run uWSGI and nginx in separate containers in a typical Docker deployment.

Regardless, many parts of this tutorial can be used with Docker, although with the aforementioned adjustments. I have done some work on this topic. This tutorial has an Ansible Playbook attached, and the tutorial/playbook are compatible with five Linux distros in multiple versions. How do I know that there were no unexpected bugs in an older version? I could grab a Vagrant image or set up a VM. I do that when I need specific testing, but doing it for each of the distros on each update would take at least half an hour, probably even more. Yeah, that needs automating. I decided to use GitHub Actions for the CI, which can run anything, as long as you provide a Dockerfile.

The Docker images were designed to support running the Playbook and testing it. But the changes, setups and patches could be a good starting point if you wanted to make your own Docker containers that could run in production. You can take a look at `the Docker files for CI <https://github.com/Kwpolska/ansible-nginx-uwsgi/tree/master/ci>`_ The images support all 5 distros using their base images, but you could probably use Alpine images, or the ``python`` docker images; be careful not to mix Python versions in the latter case.

That said, I still prefer to run without Docker, directly on the system.  Less resources wasted and less indirection.  Which is why this guide does it the traditional way.

.. [#] This reflink gives you €20 in credit (expires the next month). I earn €10 after you spend €10 of your own.

.. role:: raw-role(raw)
   :format: html
