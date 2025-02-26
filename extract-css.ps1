param(
    [string]$SourceFile,
    [string]$Pattern,
    [string]$DestFile
)

$content = Get-Content $SourceFile -Raw
if($content -match "(?s)(/\* =+\s*$Pattern\s*=+ \*/.*?)(/\* =+)") {
    $matches[1] | Out-File $DestFile -Encoding UTF8
    Write-Host "Extracted $Pattern to $DestFile"
} else {
    Write-Host "Pattern not found: $Pattern"
}
