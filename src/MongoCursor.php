<?php

namespace AlexEllo\Phalcon\MongoDB\Pagination\Adapter;

use Phalcon\Paginator\AdapterInterface;

class MongoCursor implements AdapterInterface {

	/**
	 * Cursor.
	 * 
	 * @var \MongoCursor;
	 */
	private $cursor;

	/**
	 * Limit.
	 * 
	 * @var integer
	 */
	private $limitRows;

	/**
	 * Current page.
	 * 
	 * @var integer
	 */
	private $page;

	/**
	 * Adapter constructor.
	 *
	 * @param array $config Config.
	 */
	public function __construct($config) {

		if (isset($config['data'])) {
			$this->cursor = $config['data'];
		}
		
		if (isset($config['limit'])) {
			$this->limitRows = $config['limit'];
		}
		
		if (isset($config['page'])) {
			$this->page	= $config['page'];
		}

	}

	/**
	 * Set the current page number
	 *
	 * @param integer $page Current page.
	 *
	 * @return void
	 */
	public function setCurrentPage($page) {

		$this->page = $page;
	}

	/**
	 * Returns a slice of the resultset to show in the pagination
	 *
	 * @return stdClass
	 */
	public function getPaginate() {

		
		if (!($this->cursor instanceof \MongoCursor)) {
			throw new Exception("Invalid data for paginator");
		}

		$show = intval($this->limitRows);
		$pageNumber = intval($this->page);
		$cursor = $this->cursor;
		

		if ($pageNumber <= 0) {
			$pageNumber = 1;
		}

		$page = new \stdClass();
		$number = $cursor->count();
		
		$roundedTotal = $number / floatval($show);
		$totalPages = intval($roundedTotal);

		/**
		 * Increase total_pages if wasn't integer
		 */
		if ($totalPages != $roundedTotal) {
			$totalPages++;
		}

		$page->items = $cursor->skip($show * ($pageNumber - 1))->limit($show);
		
		//Fix next
		if ($pageNumber < $totalPages) {
			$next = $pageNumber + 1;
		} else {
			$next = $totalPages;
		}

		$page->next = $next;

		if ($pageNumber > 1) {
			$before = $pageNumber - 1;
		} else {
			$before = 1;
		}

		$page->first = 1;
		$page->before = $before;
		$page->current = $pageNumber;
		$page->last = $totalPages;
		$page->total_pages = $totalPages;
		$page->total_items = $number;

		return $page;
	}

}
