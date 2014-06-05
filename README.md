phalcon-mongodb-pagination-adapter
==================================

MongoDB pagination adapter for phalcon

#usage
```php

// Current page to show
// In a controller this can be:
// $this->request->getQuery('page', 'int'); // GET
// $this->request->getPost('page', 'int'); // POST
$currentPage = (int) $_GET["page"];

// The data set to paginate
$robots = $DB->selectCollection('robots')->find();

// Create a Model paginator, show 10 rows by page starting from $currentPage
$paginator = new \AlexEllo\Phalcon\MongoDB\Pagination\Adapter\MongoCursor(
    array(
        "data" => $robots,
        "limit"=> 10,
        "page" => $currentPage
    )
);

// Get the paginated results
$page = $paginator->getPaginate();
```