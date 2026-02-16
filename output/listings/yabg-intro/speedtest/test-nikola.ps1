Set-Location $PSScriptRoot/website-nikola

$ProgressPreference = "SilentlyContinue"
$ErrorActionPreference = "Stop"

$py = $Env:NIKOLA_PY
if ($null -eq $py) {
  Write-Error '$Env:NIKOLA_PY is not set'
  Exit 1
}

function test($d, $c, $o) {
  $sum = 0
  for ($i = 0; $i -lt 11; $i++) {
    if ($d) { Remove-Item -Recurse -Force .doit* -ErrorAction SilentlyContinue }
    if ($c) { Remove-Item -Recurse -Force cache -ErrorAction SilentlyContinue }
    if ($o) { Remove-Item -Recurse -Force output -ErrorAction SilentlyContinue }

    if ($i -eq 0) {
      Write-Host "Warmup run:"
      & $py -m nikola build
      Write-Host "Warmup run complete"
      continue
    }

    $result = (Measure-Command { & $py -m nikola build -q }).TotalMilliseconds

    Write-Host $result
    $sum += $result
  }
  $avg = $sum / 10.0
  Write-Host "Average: $avg"
}

Write-Host "==="
Write-Host "Test: clean"
test $true $true $true

Write-Host "==="
Write-Host "Test: no output"
test $false $false $true

Write-Host "==="
Write-Host "Test: nothing to do"
test $false $false $false
