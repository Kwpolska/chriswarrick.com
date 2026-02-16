# Speed test for Nikola and YetAnotherBlogGenerator

## Procedure

1. Ensure Python (3.14), .NET SDK (.NET 10), PowerShell (7.x) are installed
2. Run `setup-linux.sh` / `setup-windows.ps1`
3. Run `pwsh test-nikola.ps1`, ensure `NIKOLA_PY` is set to the correct path (`$PWD/.venv/bin/python` for Linux, `$PWD\.venv\Scripts\python.exe` for Windows)
4. Run `pwsh test-yabg.ps1`, ensure `YABG_EXE` is set to the correct path (`$PWD/yabg-(release|r2r)/YetAnotherBlogGenerator(.exe)?`)

## Directory structure

* `nikola/` — Nikola source
* `YetAnotherBlogGenerator/` — YABG source
* `website-nikola/` — website for Nikola to build
* `website-yabg/` — website for YABG to build
* `cleanup.ps1`
* `setup-linux.sh`
* `setup-windows.ps1`
* `test-nikola.ps1`
* `test-yabg.ps1`

## Used commits

* Nikola source: `5f58322f516278cd1adc9d85cd3ce0a5705e9c72` <https://github.com/getnikola/nikola>
* YABG source: `033e268b866f7125e3ae3dcadfe5323a040ee50a` <https://github.com/Kwpolska/YetAnotherBlogGenerator>
* Website source for Nikola: `0c54852e87a0fc2db76f77ebec82b7dc59a155be` <https://github.com/Kwpolska/chriswarrick.com>
  * Patch: replace `plugins/projectpages` symlink with actual code from <https://github.com/getnikola/plugins> repository
* Website source for YABG: `1ca4646c1f4b0a6fa013243f997c71952d1f1767` <https://github.com/Kwpolska/chriswarrick.com>
  * Patch: in `yabg-site.yml`, replace `assetBundles:` with `assets: \n bundles:` and indent the subsequent lines (needed because used website source predates YABG commit)

### YABG website patch

```diff
-assetBundles:
-  - outputUrl: "/assets/css/all.css"
-    baseSourceDirectory: "files/assets/css"
-    files:
-      - "bootstrap.min.css"
-      - "rst_base.css"
-      - "nikola_rst.css"
-      - "code.css"
-      - "theme.css"
-  - outputUrl: "/assets/css/all-dark.css"
-    baseSourceDirectory: "files/assets/css"
-    files:
-      - "bootstrap-dark.min.css"
-      - "rst_base.css"
-      - "nikola_rst.css"
-      - "code.css"
-      - "theme.css"
-  - outputUrl: "/assets/js/all.js"
-    baseSourceDirectory: "files/assets/js"
-    files:
-      - "kw.js"
-      - "baguetteBox.min.js"
+assets:
+  bundles:
+    - outputUrl: "/assets/css/all.css"
+      baseSourceDirectory: "files/assets/css"
+      files:
+        - "bootstrap.min.css"
+        - "rst_base.css"
+        - "nikola_rst.css"
+        - "code.css"
+        - "theme.css"
+    - outputUrl: "/assets/css/all-dark.css"
+      baseSourceDirectory: "files/assets/css"
+      files:
+        - "bootstrap-dark.min.css"
+        - "rst_base.css"
+        - "nikola_rst.css"
+        - "code.css"
+        - "theme.css"
+    - outputUrl: "/assets/js/all.js"
+      baseSourceDirectory: "files/assets/js"
+      files:
+        - "kw.js"
+        - "baguetteBox.min.js"
```
