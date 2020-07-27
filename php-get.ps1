try {
    
    $php_path = (Get-Command -ErrorAction Stop "php.exe").Path
} catch {
    Write-Error -Message "An error occured, check if you have PHP installed."
}
Write-Output $php_path;