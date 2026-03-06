---
title: "Docker In WSL: A No-Frills Guide"
published: "2026-03-04 18:20:00+01:00"
updated: "2026-03-06 20:00:00+01:00"
tags: ["Docker", "Linux", "Windows", "WSL"]
category: "Programming"
description: "How to get Docker going in WSL?"
guide: true
guideEffect: "Docker is now running in WSL with automated startup and basic Windows integration"
guidePlatform: "Windows with WSL"
guideTopic: "Docker"
---
Docker is great, but how do you run Linux containers on Windows? In WSL, of course. The Docker Desktop application is certainly an option, but it eats up a lot of RAM thanks to its Electron-based GUI. It also has an LLM chatbot (because of course it does, it’s 2026 after all). Just the Docker Desktop installer takes up 598 MB. What if you want something simpler and with less RAM consumption? WSL 2 makes it really straightforward to set up Docker with some basic Windows integration.

<!-- TEASER_END -->

(Also, Docker Desktop demands payment for enterprise use; if your company fits the enterprise criteria but cannot afford $16/developer/month, run.)

*Console command conventions: `$` means Linux, `>` means Windows (PowerShell).<br>Path conventions: `/` means Linux, `\` means Windows.*

## Linux setup

You need to have WSL and a distribution installed, and you must be using WSL 2. [Read the install docs if you need to do it.](https://learn.microsoft.com/en-us/windows/wsl/install)

➡️ Start by installing Docker in the usual way for your Linux distribution of choice. I’m using Ubuntu with [the official Docker apt repository](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository). Make sure to [add yourself to the Docker group](https://docs.docker.com/engine/install/linux-postinstall).

➡️ Enable systemd support in WSL. Put those two lines into `/etc/wsl.conf`:

```ini
[boot]
systemd=true
```

You can do it like so (if you don’t have a `wsl.conf` file already):

```console
$ printf '[boot]\nsystemd=true\n' | sudo tee /etc/wsl.conf
```

➡️ Next, enable the systemd services:

```console
$ sudo systemctl enable containerd.service
$ sudo systemctl enable docker.service
```

## Exposing Docker to Windows

By default, Docker only listens on a Unix domain socket. To work with Docker from Windows, it needs to listen on a TCP socket. If you’re fine with using Docker only from the WSL terminal, and won’t run any Docker-compatible tools on the Windows side, you can basically stop reading here.

Adding a socket can be done in two ways: by adding command-line arguments, or by modifying `/etc/docker/daemon.json`. If your Docker systemd service is passing in arguments, you need to edit the service, and can’t do it using the `daemon.json` file. Let’s edit the service, as this is more likely to be what you need.

➡️ Run `sudo systemctl edit docker.service` and override the `ExecStart` command. The first `ExecStart=` line clears out the existing configuration defined in the system-provided service. The second line is the new command. To avoid breaking things, review the `ExecStart` command that is shown as the current content of your `docker.service`, and make sure you have both `-H` arguments as in my example below.

```ini
### Editing /etc/systemd/system/docker.service.d/override.conf
### Anything between here and the comment below will become the contents of the drop-in file

[Service]
ExecStart=
ExecStart=/usr/bin/dockerd -H fd:// -H tcp://127.0.0.1:2375 --containerd=/run/containerd/containerd.sock

### Edits below this comment will be discarded
```

➡️ We’re done with the WSL setup, so let’s restart WSL and make sure Docker is working:

```text
> wsl --shutdown
> wsl docker run --rm hello-world

Hello from Docker!
This message shows that your installation appears to be working correctly.
```

## Windows setup

➡️ Download the latest version of the [Docker binaries](https://download.docker.com/win/static/stable/x86_64/). Extract `docker.exe` and put it somewhere on your Windows `PATH`. I have a `~\Tools\bin` folder for things like that.

We need to tell the Docker CLI where to look for Docker. The easiest way to do this is to set the `DOCKER_HOST` environment variable. This might not be the cleanest way if you want to work with Windows containers or Docker Desktop, but you don’t, so we don’t need to bother with anything nicer.

➡️ Press the Start key, type *environment variables* (or the equivalent phrase in your Windows language) and open the environment variables editor. Add an environment variable named `DOCKER_HOST` with the value `tcp://127.0.0.1:2375`.

➡️ Restart your Terminal. In one tab, run a WSL shell. In another, run PowerShell (or cmd):

```text
> docker run -it --rm hello-world

Hello from Docker!
This message shows that your installation appears to be working correctly.
```

## Optional: Docker Compose

Docker Compose is a plugin for Docker CLI, and it not included in the Docker binary we’ve installed on Windows. It is quite easy to add it in if you want.

➡️ Download the latest version of the [Docker Compose binary](https://github.com/docker/compose/releases/latest) (`docker-compose-windows-x86_64.exe`). Move it to `~\.docker\cli-plugins\docker-compose.exe`.

```text
> mkdir ~\.docker\cli-plugins
> mv ~\Downloads\docker-compose-windows-x86_64.exe ~\.docker\cli-plugins\docker-compose.exe
```

Let’s test it out:

```text
> docker compose version
Docker Compose version v5.1.0
```

## Optional: WSL VM management

WSL does not automatically start the Linux VM. And it kills it if no user process is running inside of it.

If you’re fine with having a WSL terminal open while you’re working with Docker, you don’t need to do anything. But if you don’t want to see a WSL terminal, [make Windows start WSL when you log in](https://askubuntu.com/questions/1177273/is-there-an-easy-way-to-have-wsl-ubuntu-services-start-automatically-on-windows) and set a really large *VM Idle Timeout* in the *Optional Features* tab of the *WSL Settings* app. The maximum value accepted by the GUI[^1] is the maximum value a 32-bit integer can represent, i.e. 2147483647 ms (24 days). You can also manually add the option to `~\.wslconfig`:

```ini
[wsl2]
vmIdleTimeout=2147483647
```

[^1]: If you try to input a larger number, the app just crashes. That’s what you get when you fire your QA department and let AI take over coding.
