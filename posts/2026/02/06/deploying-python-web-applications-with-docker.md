---
title: "Deploying Python Web Applications with Docker"
published: "2026-02-06 20:45:00+01:00"
tags: ["Python", "Django", "gunicorn", "nginx", "Internet", "Linux", "Docker"]
category: "Python"
description: "Friendship ended with uWSGI Emperor, now Docker is my best friend"
guide: true
guideEffect: "your Python web app is up and running in Docker"
guidePlatform: "Linux"
guideTopic: "Python web apps and Docker"
shortlink: "pyweb-docker"
---

Ten years ago, almost to the day, I wrote a very long blog post titled [Deploying Python Web Applications with nginx and uWSGI Emperor][pyweb]. This week, I’ve migrated the Python web applications hosted on my VPS to Docker containers. Here are some reasons why, and all my Docker files to help you do this on your server.

<!-- TEASER_END -->

You can jump to the [scripts and configuration files](#scripts-and-configuration-files) if you don’t care about the theory and just want to see the end result.

## Why Docker?

Docker is a technology that has taken the software engineering world by storm. The main promise is isolation: a Docker container that works on an x86_64 Linux machine will work on any x86_64 Linux machine in the same way. Want to quickly set up PostgreSQL for testing? Just run `docker run --name postgres -e POSTGRES_PASSWORD=postgres -p 5432:5432 -d --restart=unless-stopped postgres` and wait a few seconds. Docker is great for deployment as well as production deployments, and it even supports Windows Server containers these days (whether or not this is a pleasant experience is a different question).

Of course, there is a trade-off: running something in a Docker container requires more disk space than running the same software outside of Docker would. This is because Docker containers have their own copies of *everything*: the C library, the shell, core commands, and the runtime of your favorite language. But this is not a bug, it’s a feature.

If you read [the nginx/uWSGI Emperor guide][pyweb] I wrote ten years ago, you might notice there are many cases where the configuration differs depending on the Linux distribution in use. Some distributions made logging an optional feature, others have it built in. Each distribution has a slightly different directory structure, and different users and groups for Web services. Some distros did not ship systemd service files. Red Hat is still pushing SELinux.

But uWSGI is not the only pain point. There’s also the system Python. Every distribution treats it differently and applies different customizations. Arch maps `python` to `python3`, but other distributions do not. Arch and Fedora ship a single Python package, while Debian/Ubuntu have many split packages. Taking a dependency on the system Python also makes distro upgrades harder: if the system Python is upgraded, the virtual environment needs to be recreated. (Hope you have an up-to-date `requirements.txt`!)

## Should everything be in Docker?

It depends on your definition of *everything*. I ended up with only one non-dockerized application (which requires access to resources that cannot easily be provided to it when it is in a container): an [ASP.NET Core](https://dotnet.microsoft.com/en-us/apps/aspnet) Minimal API compiled with [Native AOT](https://learn.microsoft.com/en-us/dotnet/core/deploying/native-aot/), so its maintenance burden is essentially zero. Except for that app, all web applications on my VPS, whether written in PHP, Python, or .NET, run in Docker. I don't need to figure out how to make PHP happy; I use a container image in which someone else had done the hard parts for me. I don't need to maintain Python virtual environments; they can just live inside containers. The maintenance burden for .NET is smaller, but containers still prevent issues that could be caused by removing an old version of .NET, for example. (Those apps are ASP.NET Core MVC, so they are not compatible with Native AOT.)

But I am not running the Web-facing nginx instance in Docker. Similarly, I’ve kept PostgreSQL outside of Docker, and I’ve just un-dockerized a MariaDB instance. The main difference here is that I can `apt install nginx` and get a supported and secure build, with default configuration that might be more reasonable than the default. (But then again, `apt install python3` gets you a fairly mediocre build.)

For the Python dockerization project, my requirements are fairly simple. The Docker container only needs Python, a venv with dependencies installed, and all data files of the app. The database exists outside of Docker, and I’ve already configured PostgreSQL to listen on an address accessible from within Docker (but of course, not from the public Internet). Because Django and Python have abysmal performance, static files must be served by nginx. Since I don’t want a dedicated nginx in a Docker container, the easiest solution is to mount a folder as a volume: Django runs `collectstatic` inside the container to write static files there, and the host nginx serves them.

## What should be in Docker?

There are four things that we need to put in our Docker container: Linux, Python, a venv, and a WSGI server.

### Linux and Python (base image)

The choice of a base image can affect a few things about the Docker experience. For Python, the most commonly used image is the one prepared by Docker, Inc. simply named `python`. That image has three versions, or tags in Docker parlance:

* `python:3.14` (the default), which is based on Debian (1.12GB)
* `python:3.14-slim`, which is based on Debian but with less cruft (119MB)
* `python:3.14-alpine`, which is bvased on Alpine Linux (47.4MB)

Alpine Linux images are really small, but with the caveat that they are based on the `musl` libc instead of GNU `glibc`, which means most binaries, including `manylinux` binary wheels, do not work, and special builds (`musllinux` binary wheels in this case) are required. I started with the Debian-based images (the default one for build, the slim one for production), but I switched to `python:3.14-alpine`, as the only binary dependency I have is `psycopg2`, which I build from source, but there are `musllinux` wheels of `psycopg2-binary` available.

### Virtual environments and dependency management

Python packaging is still a mess. I don’t feel like using the VC-backed `uv`, and the other big tools (like `poetry` or `pipenv`) introduce too much magic and bloat. All I need is a `requirements.txt` file I can install with pip into a venv, but without having to manually track version numbers. For that, [`pip-compile` from `pip-tools`](https://github.com/jazzband/pip-tools) is a great option. It takes a `requirements.in` file with package names and optional version ranges, and produces a `requirements.txt` with all dependencies (including transitive dependencies) pinned to exact versions. It doesn’t get much simpler than that.

There is one issue: the `requirements.txt` files produced by `pip-compile` are specific to the environment in which it was executed. So if you want a `requirements.txt` for Linux and Python 3.14, you must run `pip-compile` on Linux with Python 3.14. While this does not matter much for this project (it has very simple dependencies), I wanted to ensure the tool runs with the same Python version the container will use, and to allow building the image without having `pip-compile` installed on the development machine.

So, I quickly hacked together a tool unoriginally named [`docker-pip-compile`](https://github.com/Kwpolska/docker-pip-compile). It’s a five-line `Dockerfile` and a slightly longer shell script to help run it. That way, dependencies can be updated and the entire project can be built even without a functional system Python. The only catch here (and the reason for the hackiest line in the Dockerfile) is the fact that the package must be buildable in the environment where `pip-compile` runs, so I had to install `libpq` (the PostgreSQL client library) there.

### WSGI server

The old post used uWSGI (it’s even mentioned in the title). Sadly, [uWSGI has been in maintenance mode since 2022](https://github.com/unbit/uwsgi/commit/5838086dd4490b8a55ff58fc0bf0f108caa4e079). On the other hand, [gunicorn](https://github.com/benoitc/gunicorn) is doing pretty well, so I used that. I also decided to add [uvicorn](https://uvicorn.dev/) to the mix, because why not.

## Scripts and configuration files

You might be able to find a more up-to-date version of those scripts in the [nikola-users](https://github.com/getnikola/nikola-users/tree/master/docker) repository.

### Dockerfile

The Dockerfile is pretty straightforward. To save a little disk space, I use a multi-stage build. The build stage sets up a virtual environment, installs packages into it, and copies files into `/app`. The final stage installs `libpq` (the PostgreSQL client library, needed for `psycopg2`) and copies `/venv` and `/app` from the build stage. (The disk space savings come from not having `build-base` and `libpq-dev` in the final image.)

```dockerfile
FROM python:3.14-alpine AS build
WORKDIR /app
RUN apk add build-base libpq libpq-dev
RUN python -m venv /venv && /venv/bin/python -m pip install --no-cache-dir -U pip
COPY requirements.txt /app
RUN /venv/bin/pip install --no-cache-dir -r requirements.txt
COPY nikolausers /app/nikolausers
COPY sites /app/sites
COPY templates /app/templates
COPY manage.py docker/docker-entrypoint.sh /app/
RUN chmod +x /app/docker-entrypoint.sh

FROM python:3.14-alpine
WORKDIR /app
RUN apk add libpq
COPY --from=build /venv /venv
COPY --from=build /app /app
ENTRYPOINT ["/app/docker-entrypoint.sh"]
```

### docker-entrypoint.sh

The entrypoint is fairly simple as well. Before starting gunicorn, we need to run migrations and collect static files. To avoid repeating this on container restarts, we create a marker file inside the container to indicate whether setup has already completed. I use three workers, which should be enough for those sites.

```bash
#!/bin/sh
statefile=/tmp/migrated
if [ ! -f "$statefile" ]; then
    /venv/bin/python manage.py collectstatic --noinput
    /venv/bin/python manage.py migrate --noinput
    touch "$statefile"
fi
exec /venv/bin/python -m gunicorn --bind 0.0.0.0:6868 nikolausers.asgi:application -k uvicorn_worker.UvicornWorker -w 3
```

### docker-compose.yml

I mentioned that only one service runs inside Docker, since nginx and PostgreSQL live outside. Docker Compose may feel unnecessary with just one service, but it is still a convenient way to keep the required configuration in a file.

```bash
services:
  nikolausers:
    restart: unless-stopped
    image: nikolausers:latest
    ports:
      - 127.0.0.1:6868:6868
    user: "33:33" # www-data on Debian
    environment:
      #SECRET_KEY: ""
      #DB_NAME: ""
      #DB_USER: ""
      #DB_PASSWORD: ""
      #DB_HOST: ""
      #EMAIL_HOST: ""
    volumes:
      - ./static:/app/static
```

### nginx

The nginx configuration is as simple as it gets:

```nginx
server {
    # skip standard host config...

    location / {
        proxy_pass http://127.0.0.1:6868/;
        include proxy_params;
    }

    location /static {
        alias /srv/users.getnikola.com/static;
    }
}
```

## Conclusion

In just two evenings, I got rid of the venv and system Python maintenance burden. When I upgrade to Ubuntu 26.04 in a few months, my Python apps will just work with no extra steps needed. Docker is a lot of fun.

[pyweb]: https://chriswarrick.com/blog/2016/02/10/deploying-python-web-apps-with-nginx-and-uwsgi-emperor/
