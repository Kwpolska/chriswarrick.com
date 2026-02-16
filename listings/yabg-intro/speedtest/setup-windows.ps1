Remove-Item -Recurse -Force '.venv' -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force 'yabg-release' -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force 'yabg-r2r' -ErrorAction SilentlyContinue
py -m venv .venv
& ".venv\Scripts\pip" install './nikola[extras]'
& ".venv\Scripts\pip" install './YetAnotherBlogGenerator/PygmentsAdapter'
dotnet publish -c Release ./YetAnotherBlogGenerator/YetAnotherBlogGenerator/YetAnotherBlogGenerator.csproj -o ./yabg-release
dotnet publish -c Release ./YetAnotherBlogGenerator/YetAnotherBlogGenerator/YetAnotherBlogGenerator.csproj -p PublishReadyToRun=true -o ./yabg-r2r
"outputFolder: ""output""`npygmentsAdapterPythonBinary: ""$PWD\.venv\Scripts\python.exe""" | Out-File -Encoding ASCII website-yabg/yabg-local.yml
