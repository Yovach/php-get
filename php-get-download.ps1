$SiteAdress = "https://pecl.php.net/package-stats.php";

# we fetch the list of all packages from pecl.php.net
$HttpContent = Invoke-WebRequest -URI $SiteAdress;

# we only take links of packages and get innerText and href properties 
$links = ($HttpContent.Links | Where-Object {$_.href -like "*/package/*"}) | Select-Object -Property innerText,href;

# we export the result to output.json
ConvertTo-Json $links > .\output.json;