try {
    
    $php_path = (Get-Command -ErrorAction Stop "php.exe").Path
} catch {
    Write-Error -Message "An error occured, check if you have PHP installed."
}

if(!(Test-Path packages.json))
{
    Write-Output "Downloading packages.json..";
    .\php-get-download.ps1;
}
# Write-Output $php_path;

$ext = $args[0];
if (!$ext) {
    Write-Output "Please indicate an extension to be installed";
    Break; 
}
Write-Output $ext;