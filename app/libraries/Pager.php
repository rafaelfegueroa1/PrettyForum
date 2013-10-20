<?php
/**
 * User: Rogier
 * Date: 18-10-13
 * Time: 22:47
 *
 */

class Pager {

    private $total;
    private $per_page;
    private $url;

    public function __construct($total, $per_page, $url)
    {
        $this->total = $total;
        $this->per_page = $per_page;
        $this->url = $url;
        $this->listItems = array();
    }

    public function links()
    {
        if($this->total <= $this->per_page)
        {
            return '';
        }
        $str = '<ul class="paginate-small">';

        $page = 1;
        for($i = 0; $i < $this->total; $i += $this->per_page)
        {

            $this->listItems[] = '<li><a href="'.$this->url.'?page='.$page.'">'.$page.'</a></li>';
            $page++;
        }

        if(count($this->listItems) >= 6)
        {
            $page--;
            $this->listItems = array_slice($this->listItems, 0, 6);
            $this->listItems[4] = '<li>...</li>';
            $this->listItems[5] = '<li><a href="'.$this->url.'?page='.$page.'">'.$page.'</a></li>';
        }
        $str .= implode($this->listItems);
        $str .= '</ul>';
        return $str;
    }

}