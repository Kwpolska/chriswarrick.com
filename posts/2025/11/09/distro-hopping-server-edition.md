---
title: "Distro Hopping, Server Edition"
published: "2025-11-09 19:00:00+01:00"
updated: "2025-11-23 18:30:00+01:00"
tags: ["Linux", "Ubuntu", "Fedora", "Python", "Docker", "Django"]
category: "Linux"
description: "I’ve recently migrated my VPS from Fedora to Ubuntu. Here’s a list of things that might be useful to keep in mind before, during, and after a migration of a server that hosts publicly accessible Web sites and applications, as well as other personal services, and how to get rid of the most annoying parts of Ubuntu."
---
I’ve recently migrated my VPS from Fedora to Ubuntu. Here’s a list of things that might be useful to keep in mind before, during, and after a migration of a server that hosts publicly accessible Web sites and applications, as well as other personal services, and how to get rid of the most annoying parts of Ubuntu.

<!-- TEASER_END -->

## Why switch?

Fedora is a relatively popular distro, so it’s well supported by software vendors. Its packagers adopt a no-nonsense approach, making very little changes that deviate from the upstream.

Ubuntu is not my favorite distro, far from it. While it is perhaps the most popular distro out there, its packages contain many more patches compared to Fedora, and Canonical (the company behind Ubuntu) are famous for betting on the wrong horse (Unity, upstart, Mir…). But one thing Ubuntu does well is stability. Fedora makes releases every 6 months, and those releases are supported for just 13 months, which means upgrading at least every year. Every upgrade may introduce incompatibilities, almost every upgrade requires recreating Python venvs. That gets boring fast, and it does not necessarily bring benefits. Granted, the Fedora system upgrade works quite well, and I upgraded through at least eight releases without a re-install, but I would still prefer to avoid it. That’s why I went with Ubuntu LTS, which is supported for five years, with a new release every two years, but which still comes with reasonably new software (and with many third-party repositories if something is missing or outdated).

## Test your backups

I have a backup “system” that’s a bunch of Bash scripts. After upgrading one of the services that is being backed up, the responsible script started crashing, and thus backups stopped working. Another thing that broke was e-mails from cron, so I didn’t know anything was wrong.

While I do have full disk backups enabled at [Hetzner](https://hetzner.cloud/?ref=Qy1lehF8PwzP) *(disclaimer: referral link)*, my custom backups are more fine-grained (e.g. important configuration files, database dumps, package lists), so they are quite useful in migrating between OSes.

So, here’s a reminder not only to test your backups regularly, but also to make sure they are being created at all, and to make sure cron can send you logs somewhere you can see them.

Bonus cron tip: set `MAILFROM=` and `MAILTO=` in your crontab if your SMTP server does not like the values cron uses by default.

## Think about IP address reassignment (or pray to the DNS gods)

A new VPS or cloud server probably means a new IP address. But if you get a new IP address, that might complicate the migration of your publicly accessible applications. If you’re proxying all your Web properties through Cloudflare or something similar, that’s probably not an issue. But if you have a raw A record somewhere, things can get complicated. DNS servers and operating systems do a lot of caching. The conventional wisdom is to wait 24 or even 48 hours after changing DNS values. This might be true if your TTL is set to a long value, but if your TTL is short, the only worry are DNS servers that ignore TTL values and cache records for longer. If you plan a migration, it’s good to check your TTL well in advance, and not worry too much about broken DNS servers.

But you might not need a new IP. Carefully review your cloud provider’s IP management options before making any changes. Hetzner is more flexible than other hosts in this regard, as it is possible to [move primary public IP addresses (not “floating” or “elastic” IPs) between servers](https://docs.hetzner.com/cloud/servers/primary-ips/faq), as long as you’re okay with a few minutes’ downtime (you will need to shut down the source and destination servers).

If you’re not okay with any downtime, you would probably want to leverage the floating/elastic IP feature, or hope DNS propagates quickly enough.

## Trim the fat

My VPS ran a lot of services I don’t need anymore, but never really got around to decommissioning. For example, I had a full Xfce install with VNC access (the VNC server was only running when needed). I haven’t actually used the desktop for ages, so I just dropped it.

I also had an OpenVPN setup. It was useful years ago, when mobile data allowances were much smaller and speeds much worse. These days, I don’t use public WiFi networks at all, unless I’m going abroad, and I just buy one month of [Mullvad VPN](https://mullvad.net/) for €5 whenever that happens. So, add another service to the “do not migrate” list.

One thing that I could not just remove was the e-mail server. Many years ago, I ran a reasonably functional e-mail server on my VPS. I’ve since then migrated to [Zoho Mail](https://www.zoho.com/mail/) (which costs €10.80/year), in part due to IP reputation issues after changing hosting providers, and also to avoid having to fight spam. When I did that, I kept Postfix around, but as a local server for things like cron or Django to send e-mail with, and I configured it to send all e-mails via Zoho. But I did not really want to move over all the configuration, hoping that Ubuntu’s Postfix packages can work with my hacked together config from Fedora. So I replaced the server with [OpenSMTPD](https://www.opensmtpd.org/) (from the OpenBSD project), and all the Postfix configuration files with just one short configuration file:

```text
table aliases file:/etc/aliases
table secrets file:/etc/mail-secrets

listen on localhost
listen on 172.17.0.1 # Docker

action "relay" relay host smtp+tls://smtp@smtp.example.net:587 auth <secrets> mail-from "@example.com"

match from any for any action "relay"
```

## Dockerize everything…

My server runs a few different apps, some of which are exposed on the public Internet, while some do useful work in the background. The services I have set up most recently are containerized with the help of Docker. The only Docker-based service that was stateful (and did not just use folders mounted as volumes) was a MariaDB database. Migrating that is straightforward with a simple dump-and-restore.

Of course, not everything on my server is in Docker. The public-facing nginx install isn’t, and neither is PostgreSQL (but that was also a quick dump-and-restore migration with some extra steps).

## …especially Python

But then, there are the Python apps. Python the language is cool (if a little slow), but the packaging story is a [total](https://chriswarrick.com/blog/2023/01/15/how-to-improve-python-packaging/) dumpster [fire](https://chriswarrick.com/blog/2024/01/15/python-packaging-one-year-later/).

By the way, here’s a quick recap of 2024/2025 in Python packaging: the most hyped Python package manager (`uv`) is written in Rust, which screams “Python is a toy language in which you can’t even write a tool as simple as a package manager”. (I know, dependency resolution is computationally expensive, so doing *that* in Rust makes sense, but everything else could easily be done in pure Python. And no, the package manager should not manage Python installs.) Of course, almost all the other contenders listed in my 2023 post are still being developed. On the standards front, the community finally produced a lockfile standard after years of discussions.

Anyway, I have three Python apps. One of them is [Isso](https://isso-comments.de/), which is providing the comments box below this post. I used to run a modified version of Isso a long time ago, but I don’t need to anymore. I looked at the docs, and they offer [a pre-built Docker image](https://isso-comments.de/docs/reference/installation/#using-docker), which means I could just quickly deploy it on my server with Docker and skip the pain of managing Python environments.

The other two apps are Django projects built by yours truly. They are not containerized, they exist in venvs created using the system Python. Moving venvs between machines is generally impossible, so I had to re-create them. Of course, I hit a deprecation, because the Python maintainers (especially in the packaging world) does not understand their responsibility as maintainers of the most popular programming language. This time, it was caused by [an old editable install with setuptools (using setup.py develop, not PEP 660)](https://github.com/pypa/pip/issues/11457), and installs with more recent pip/setuptools versions would not have this error… although [some people want to remove the fallback to setuptools if there is no pyproject.toml](https://discuss.python.org/t/do-we-want-to-keep-the-build-system-default-for-pyproject-toml/104759), so you need to stay up to date with the whims of the Python packaging industry if you want to use Python software.

**Update 2026-02-06:** [I migrated the two Django apps to Docker and wrote a post about it.](/blog/2026/02/06/deploying-python-web-applications-with-docker/)

## Don’t bother with ufw

Ubuntu ships with `ufw`, the “uncomplicated firewall”, in the default install. I was previously using `firewalld`, a Red Hat-adjacent project, but I decided to give ufw a try. Since if it’s part of the default install, it might be supported better by the system.

It turns out that Docker and ufw [don’t play together](https://docs.docker.com/engine/network/packet-filtering-firewalls/#docker-and-ufw). [Someone has built a set of rules that are supposed to fix it](https://github.com/chaifeng/ufw-docker?tab=readme-ov-file#solving-ufw-and-docker-issues), but that did not work for me.

Docker [does integrate with firewalld](https://docs.docker.com/engine/network/packet-filtering-firewalls/#integration-with-firewalld), and Ubuntu has packages for it, so I just installed it, enabled the services that need to be publicly available and things were working again.

*Update (2025-11-23):* The iptables integration was not very stable on my Ubuntu system, so I disabled the iptables integration and switched to [a simpler config in firewalld only](https://dev.to/soerenmetje/how-to-secure-a-docker-host-using-firewalld-2joo).

## Kill the ads (and other nonsense too)

Red Hat makes money by selling a stable OS with at least 10 years of support to enterprises, and their free offering is Fedora, with just 13 months of support; RHEL releases are branched off from Fedora. SUSE also sells SUSE Linux Enterprise and has openSUSE as the free offering (but the relationship between the paid and free version is more complicated).

Ubuntu chose a different monetization strategy: the enterprise offering is the same OS as the free offering, but it gets extra packages and extra updates. The free OS advertises the paid services. It is fairly simple to get rid of them all:

```text
sudo apt autoremove ubuntu-pro-client
sudo chmod -x /etc/update-motd.d/*
```

Also, Ubuntu installs snap by default. Snap is a terrible idea. Luckily, there are no snaps installed by default on a Server install, so we can just remove `snapd`. We’ll also remove `lxd-installer` to save ~25 kB of disk space, since the installer requires snap, and lxd is another unsuccessful Canonical project.

```text
sudo apt autoremove snapd lxd-installer
```

## The cost of downgrading

Going from Fedora 42 (April 2025) to Ubuntu 24.04 (April 2024) means some software will be downgraded in the process. In general, this does not matter, as most software does not mind downgrades as much. One notable exception is WeeChat, the IRC client, whose config files are versioned, and Ubuntu’s version is not compatible with the one in Fedora. But here’s where Ubuntu’s popularity shines: [WeeChat has its own repositories for Debian and Ubuntu](https://weechat.org/download/debian/), so I could just get the latest version without building it myself or trying to steal packages from a newer version.

Other than WeeChat, I haven’t experienced any other issues with software due to a downgrade. Some of it is luck (or not using new/advanced features), some of it is software caring about backwards compatibility.

## Conclusion

Was it worth it? Time will tell. Upgrading Fedora itself was not that hard, and I expect Ubuntu upgrades to be OK too — the annoying part was cleaning up and getting things to work after the upgrade, and the switch means I will have to do it only every 2-4 years instead of every 6-12 months.

The switchover took a few hours, especially since I didn’t have much up-to-date documentation of what is actually installed and running, and there are always the minor details where distros differ that may require adjusting to. I think a migration like this is worth trying if rolling-release or frequently-released distros are too unstable for your needs.
