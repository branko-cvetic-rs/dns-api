# dns-api
RestFull API for simple DNS management

# Implemented points
* **list** - List all domains (PATH: $webroot/dns-api/records/get.php)
* **get** - Return domain name + all dns records (PATH: $webroot/dns-api/domains/list.php)
* **search** - Search domains by name, may include DNS part (PATH: $webroot/dns-api/records/search.php?s=$search_domain) 
	eg if we have domains abc.net and DNS record www, mail, ftp below are examples:
	search for "abc" return abc.net
	search for www.abc or www.abc.net return www.abc.net
	search for www1.abc.net returns empty
* **create** - Add new domain or return and error if it either exists or in invalid format (PATH: $webroot/dns-api/domains/create.php)
* **create-record** - Add new dns record for give domain name. If it does not exists create record. if anything is invalid return error (PATH: $webroot/dns-api/records/create-record.php)
