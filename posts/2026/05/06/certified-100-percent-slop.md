---
title: "Certified 100% Slop"
published: "2026-05-06 21:00:00+02:00"
tags: ["AI", "Claude", "Copilot", "GitHub", "LLM", "programming"]
category: "Programming"
description: "How to spot open-source projects that had near-zero human involvement."
---

Recently, a port of Notepad++ to macOS has been making the rounds online, mainly due to the project using the Notepad++ name without permission and in breach of the original author’s trademark. But does it deserve any attention, or is it just AI slop all the way down?

<!-- TEASER_END -->

After [the trademark dispute](https://notepad-plus-plus.org/news/npp-trademark-infringement/), the port was renamed to Nextpad++. In the contributors section of its [GitHub page](https://github.com/nextpad-plus-plus/nextpad-plus-plus-macos), there are three accounts. The middle one belongs to Claude, Anthropic’s LLM; specifically, this GitHub account is used by [Claude Code](https://claude.com/product/claude-code), an *agentic coding tool*. Or, alternatively, a vibe-coded app that will vibe-code anything you ask for, as long as you pay for the (temporarily heavily subsidized) subscription fee.

<figure class="figure border rounded float-end ms-2 mb-2">
<a href="/images/2026-05-06-nextpad-plus-plus-macos-contributors.png" class="image-reference">
<img class="figure-img rounded mb-0" src="/images/2026-05-06-nextpad-plus-plus-macos-contributors.thumbnail.png" alt="The contributors of Nextpad++.">
</a>
<figcaption class="figure-caption text-center mt-1 mb-1">The contributors of Nextpad++.</figcaption>
</figure>

There were 191 commits in the repository at the time of writing. Let’s check out the [contributors](https://github.com/nextpad-plus-plus/nextpad-plus-plus-macos/graphs/contributors) page:

* `@aletik`: 188 commits, 747k lines added, 129k lines deleted
* `@claude`: 181 commits, 734k lines addded, 129k lines deleted
* `@christianbaumann`: 2 commits, 48 lines added, 44 lines deleted

This codebase was written almost entirely by Claude. Furthermore, it is easy to notice Claudisms when looking at commits not attributed to Claude, such as [section headers made out of em-dashes in the initial commit (11k lines added)](https://github.com/nextpad-plus-plus/nextpad-plus-plus-macos/blob/71b1a8b2fddc00af3706e69e5bb0abcc63e23a2f/src/ColumnEditorPanel.mm#L4). Another [commit without a Claude attribution](https://github.com/nextpad-plus-plus/nextpad-plus-plus-macos/commit/480f2700b7f5950c39b5748ea99145f88bd8a048) has an extremely long commit message — again, very Claude-y, very em-dash-y[^1].

The Claude Code attribution is the last line of the commit message. For example, here’s a random commit[^2]:

```text
$ git show --stat 8e2b0749fc4354532d89572fb7b6ae7ec6bf8e41
commit 8e2b0749fc4354532d89572fb7b6ae7ec6bf8e41
Author: Andrey Letov <aletik@gmail.com>
Date:   Fri Apr 24 17:57:25 2026 -0400

    Bump host to 1.0.5

    CMakeLists.txt (BUNDLE_VERSION + SHORT_VERSION_STRING) and
    resources/Info.plist (CFBundleVersion + CFBundleShortVersionString)
    roll from 1.0.4 → 1.0.5. Local DMG built but not pushed / notarized
    yet — release ships in a couple of days.

    Headline addition since 1.0.4:
      - Pin / unpin toggle on popped side panels (defaults to unpinned;
        was forced always-on-top in v1.0.4).

    Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>

 CMakeLists.txt       | 4 ++--
 resources/Info.plist | 4 ++--
 2 files changed, 4 insertions(+), 4 deletions(-)
```

GitHub picks up those attribution lines and shows them when viewing commits (“**user** and **claude** committed”), and includes them in the commit tally on the contributors page. But how does Git know to add this attribution? Is it some magic feature where Claude secretly writes a file for Git to pick up? Nope! Claude will include that attribution when it commits the changes. But by default, Claude will *not* commit anything when it finishes implementing your request. You need to **explicitly** ask it to commit, or include that in your instructions files (e.g., `CLAUDE.md`).[^3]

If someone were to review the changes before committing, they would most likely do it in a code editor, navigating using the Git pane, so they see the commit box right in front of them, and they could just spend a minute writing a reasonable commit message. But nah, why bother with code review, let’s just [vibe our way onto MacRumors](https://www.macrumors.com/2026/04/29/notepad-plus-plus-editor-comes-to-mac/).

So: if you see the Claude attribution appear in a commit, you can be sure the commit is unreviewed [slop](https://en.wikipedia.org/wiki/AI_slop). And if virtually all commits in a project have a Claude attribution, the project is **certified 100% slop**. Especially since [you can disable the attribution in Claude configuration](https://code.claude.com/docs/en/settings#attribution-settings), so the authors of those projects don’t care at all about hiding their code’s sloppiness.

[^1]: LLMs learning to use em-dashes from well-typeset books has ruined good typography. I’ve been using a [custom keyboard layout](https://chriswarrick.com/projects/kwkeyboard/) since 2012, with em-dashes and “smart” quotes, but I’ve significantly limited the use of those characters since they became the tell-tale signs of LLM writing. (This post is 100% human-written.)
[^2]: I picked a relatively recent one, with a fairly short commit message, an Unicode arrow (→), an em-dash, and where the only change is updating the version numbers in two files. This is why we can’t have nice things (and RAM).
[^3]: It should be noted that VS Code has recently introduced a feature where it will sneakily add a Copilot attribution, and they managed to [make it apply it to **all** commits, even when AI features are disabled](https://github.com/microsoft/vscode/pull/310226) (why was that `all` value even implemented?), but [that feature attributes Copilot](https://github.com/microsoft/vscode/blob/main/extensions/git/src/repository.ts#L1489), not Claude, even if you’re using Claude models within VS Code.
