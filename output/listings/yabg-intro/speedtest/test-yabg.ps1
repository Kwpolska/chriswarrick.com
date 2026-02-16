Set-Location $PSScriptRoot/website-yabg

$ProgressPreference = "SilentlyContinue"
$ErrorActionPreference = "Stop"

$yabg = $Env:YABG_EXE
if ($null -eq $yabg) {
  Write-Error '$Env:YABG_EXE is not set'
  Exit 1
}

function test($c, $o) {
  $sum = 0
  for ($i = 0; $i -lt 11; $i++) {
    if ($c) { Remove-Item -Recurse -Force '.yabg-cache.sqlite3' -ErrorAction SilentlyContinue }
    if ($o) { Remove-Item -Recurse -Force output -ErrorAction SilentlyContinue }

    if ($i -eq 0) {
      Write-Host "Warmup run:"
      & $yabg
      Write-Host "Warmup run complete"
      continue
    }

    $result = (Measure-Command { & $yabg }).TotalMilliseconds

    Write-Host $result
    $sum += $result
  }
  $avg = $sum / 10.0
  Write-Host "Average: $avg"
}

Write-Host "==="
Write-Host "Test: clean"
test $true $true

Write-Host "==="
Write-Host "Test: no output"
test $false $true

Write-Host "==="
Write-Host "Test: nothing to do"
test $false $false
