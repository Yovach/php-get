$SiteAdress = "https://windows.php.net/downloads/pecl/releases/";

# we fetch the list of all packages from pecl.php.net
$HttpContent = Invoke-WebRequest -URI $SiteAdress;

# we only take links of packages and get innerText and href properties 
$links = ($HttpContent.Links | Where-Object {$_.href -like "*/downloads/pecl/releases/*"}) | Select-Object -Property @{N='name';E={$_.innerText}};

# we export the result to packages.json
ConvertTo-Json $links > .\packages.json;