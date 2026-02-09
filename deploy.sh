#!/bin/sh
rsync -rav --exclude-from=deploy-ignores --del output/ nayru:/srv/chriswarrick.com
