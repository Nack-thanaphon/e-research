<?php
class Paginator {
    public $items_per_page;
    public $items_total;
    public $current_page;
    public $num_pages;
    public $mid_range;
    public $low;
    public $high;
    public $limit;
    public $return;
    public $default_ipp;
    public $querystring;
    public $url_next;
    public $range; // Define the range property
    public $start_range; // Define the start_range property
    public $end_range; // Define the end_range property

    public function __construct() {
        // Set default values for pagination
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = $this->default_ipp ?? 10; // Fallback if default_ipp is not set
        $this->url_next = $this->url_next ?? ''; // Fallback if url_next is not set
        
    }

    public function paginate() {
        // Validate items per page
        if (!is_numeric($this->items_per_page) || $this->items_per_page <= 0) {
            $this->items_per_page = $this->default_ipp;
        }

        // Calculate total number of pages
        $this->num_pages = ceil($this->items_total / $this->items_per_page);

        // Ensure the current page is within valid range
        if ($this->current_page < 1 || !is_numeric($this->current_page)) {
            $this->current_page = 1;
        }

        if ($this->current_page > $this->num_pages) {
            $this->current_page = $this->num_pages;
        }

        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        // Pagination logic for more than 10 pages
        if ($this->num_pages > 10) {
            $this->return = ($this->current_page != 1 && $this->items_total >= 10)
                ? "<a href=\"{$this->url_next}{$prev_page}#top_page\" class='stylenumpage'>«</a> "
                : '';

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            // Adjust range if out of bounds
            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }

            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }

            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                // Create "..." separator for skipped pages
                if ($this->range[0] > 2 && $i == $this->range[0]) {
                    $this->return .= " ... ";
                }

                // Create pagination links
                if ($i == 1 || $i == $this->num_pages || in_array($i, $this->range)) {
                    $this->return .= ($i == $this->current_page && $_GET['Page'] != 'All')
                        ? "<a href=\"#top_page\" class='stylenumpage' style='color:#FF0000;'><b>$i</b></a> "
                        : "<a href=\"{$this->url_next}{$i}#top_page\" class='stylenumpage'>$i</a> ";
                }

                // Add "..." at the end of the range
                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->range[$this->mid_range - 1]) {
                    $this->return .= " ... ";
                }
            }

            // Next page link
            $this->return .= ($this->current_page != $this->num_pages && $this->items_total >= 10 && $_GET['Page'] != 'All')
                ? "<a href=\"{$this->url_next}{$next_page}#top_page\" class='stylenumpage'>»</a>\n"
                : "\n";
        } else {
            // Simple pagination for fewer pages
            for ($i = 1; $i <= $this->num_pages; $i++) {
                $this->return .= ($i == $this->current_page)
                    ? "<a href=\"#top_page\" class='stylenumpage' style='color:#FF0000;'><b>$i</b></a> "
                    : "<a href=\"{$this->url_next}{$i}#top_page\" class='stylenumpage'>$i</a> ";
            }
        }

        // Set limit for SQL queries
        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = ($_GET['ipp'] == 'All') ? $this->items_total : ($this->current_page * $this->items_per_page) - 1;
        $this->limit = ($_GET['ipp'] == 'All') ? '' : " LIMIT $this->low, $this->items_per_page";
    }

    public function display_pages() {
        return $this->return;
    }

    public function mb_substr($str, $start, $length = null) {
        if ($length === null) {
            return mb_substr($str, $start);
        } else {
            return mb_substr($str, $start, $length);
        }
    }

    public function dateThai_edo($strDate) {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
}