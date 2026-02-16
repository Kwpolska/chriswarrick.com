#!/bin/sh
rm -rf .venv yabg-release yabg-r2r
python3 -m venv .venv
.venv/bin/pip install './nikola[extras]'
.venv/bin/pip install './YetAnotherBlogGenerator/PygmentsAdapter'
dotnet publish -c Release ./YetAnotherBlogGenerator/YetAnotherBlogGenerator/YetAnotherBlogGenerator.csproj -o ./yabg-release
dotnet publish -c Release ./YetAnotherBlogGenerator/YetAnotherBlogGenerator/YetAnotherBlogGenerator.csproj -p PublishReadyToRun=true -o ./yabg-r2r
printf 'outputFolder: "output"\npygmentsAdapterPythonBinary: "'"$PWD"'/.venv/bin/python"\n' > website-yabg/yabg-local.yml
