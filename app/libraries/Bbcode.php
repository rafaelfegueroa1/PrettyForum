<?php
/**
 * BBCode to HTML converter
 *
 * Created by Kai Mallea (kmallea@gmail.com)
 *
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */


class BbCode {
    protected $bbcode_table = array();

    public function __construct () {

        // Replace [b]...[/b] with <strong>...</strong>
        $this->bbcode_table["/\[b\](.*?)\[\/b\]/is"] = function ($match) {
            return "<strong>$match[1]</strong>";
        };


        // Replace [i]...[/i] with <em>...</em>
        $this->bbcode_table["/\[i\](.*?)\[\/i\]/is"] = function ($match) {
            return "<em>$match[1]</em>";
        };


        // Replace [code]...[/code] with <pre><code>...</code></pre>
        $this->bbcode_table["/\[code\](.*?)\[\/code\]/is"] = function ($match) {
            return "<pre><code>$match[1]</code></pre>";
        };






        // Replace [size=30]...[/size] with <span style="font-size:30%">...</span>
        $this->bbcode_table["/\[size=(\d+)\](.*?)\[\/size\]/is"] = function ($match) {
            return "<span style=\"font-size:$match[1]%\">$match[2]</span>";
        };


        // Replace [s] with <del>
        $this->bbcode_table["/\[s\](.*?)\[\/s\]/is"] = function ($match) {
            return "<del>$match[1]</del>";
        };


        // Replace [u]...[/u] with <span style="text-decoration:underline;">...</span>
        $this->bbcode_table["/\[u\](.*?)\[\/u\]/is"] = function ($match) {
            return '<span style="text-decoration:underline;">' . $match[1] . '</span>';
        };


        // Replace [center]...[/center] with <div style="text-align:center;">...</div>
        $this->bbcode_table["/\[center\](.*?)\[\/center\]/is"] = function ($match) {
            return '<div style="text-align:center;">' . $match[1] . '</div>';
        };


        // Replace [color=somecolor]...[/color] with <span style="color:somecolor">...</span>
        $this->bbcode_table["/\[color=([#a-z0-9]+)\](.*?)\[\/color\]/is"] = function ($match) {
            return '<span style="color:'. $match[1] . ';">' . $match[2] . '</span>';
        };


        // Replace [email]...[/email] with <a href="mailto:...">...</a>
        $this->bbcode_table["/\[email\](.*?)\[\/email\]/is"] = function ($match) {
            return "<a class='forum-link' href=\"mailto:$match[1]\">$match[1]</a>";
        };


        // Replace [email=someone@somewhere.com]An e-mail link[/email] with <a href="mailto:someone@somewhere.com">An e-mail link</a>
        $this->bbcode_table["/\[email=(.*?)\](.*?)\[\/email\]/is"] = function ($match) {
            return "<a class='forum-link' href=\"mailto:$match[1]\">$match[2]</a>";
        };


        // Replace [url]...[/url] with <a href="...">...</a>
        $this->bbcode_table["/\[url\](.*?)\[\/url\]/is"] = function ($match) {
            return "<a class='forum-link' href=\"$match[1]\" target='_blank'>$match[1]</a>";
        };


        // Replace [url=http://www.google.com/]A link to google[/url] with <a href="http://www.google.com/">A link to google</a>
        $this->bbcode_table["/\[url=(.*?)\](.*?)\[\/url\]/is"] = function ($match) {
            return "<a class='forum-link' href=\"$match[1]\" target='_blank'>$match[2]</a>";
        };


        // Todo: Give images classes with max size of x * x
        // Replace [img]...[/img] with <img src="..."/>
        $this->bbcode_table["/\[img\](.*?)\[\/img\]/is"] = function ($match) {
            return "<img src=\"$match[1]\"/>";
        };


        // Replace [list]...[/list] with <ul><li>...</li></ul>
        $this->bbcode_table["/\[list\](.*?)\[\/list\]/is"] = function ($match) {
            $match[1] = preg_replace_callback("/\[\*\]([^\[\*\]]*)/is", function ($submatch) {
                return "<li>" . preg_replace("/[\n\r?]$/", "", $submatch[1]) . "</li>";
            }, $match[1]);

            return "<ul>" . preg_replace("/[\n\r?]/", "", $match[1]) . "</ul>";
        };



        // Replace [youtube]...[/youtube] with <iframe src="..."></iframe>
        $this->bbcode_table["/\[youtube\](?:http?:\/\/)?(?:www\.)?youtu(?:\.be\/|be\.com\/watch\?v=)([A-Z0-9\-_]+)(?:&(.*?))?\[\/youtube\]/i"] = function ($match) {
            return "<iframe class=\"youtube-player\" type=\"text/html\" width=\"640\" height=\"385\" src=\"http://www.youtube.com/embed/$match[1]\" frameborder=\"0\"></iframe>";
        };


        $this->bbcode_table["/\[spoiler\](.*?)\[\/spoiler\]/is"] = function($match)
        {
            return '<div class="panel panel-primary"><div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#'.md5($match[0]).'">Spoiler</a></h4></div><div id="'.md5($match[0]).'" class="panel-collapse collapse"><div class="panel-body">'.$match[1].'</div></div></div>';
        };
    }


    // Replace [quote=User]...[/quote] with <blockquote><p>...</p></blockquote>
    // Also replaces nested quotes
    public function quote($str)
    {
        while(preg_match("/\[quote=(.*?)\](.*?)\[\/quote\]/", $str))
        {
            $str = preg_replace_callback("/\[quote=(.*?)\](.*?)\[\/quote\]/", function ($match) {
                $match[2] = preg_replace("/\[img\](.*?)\[\/img\]/is", "<a class='forum-link' href='$1' target='_blank'>[ Image ]</a>", $match[2]);
                return "<div><span class='forum-link'>".$match[1]."</span> <blockquote><p>$match[2]</p></blockquote></div>";
            }, $str);
        }
        while(preg_match("/\[quote\](.*?)\[\/quote\]/is", $str))
        {
            $str = preg_replace_callback("/\[quote\](.*?)\[\/quote\]/is", function ($match) {
                $match[1] = preg_replace("/\[img\](.*?)\[\/img\]/is", "<a class='forum-link' href='$1' target='_blank'>[ Image ]</a>", $match[1]);
                return "<div><span class='forum-link'>Quote</span> <blockquote><p>$match[1]</p></blockquote></div>";
            }, $str);
        }
        return $str;

    }

    public function toHTML ($str, $escapeHTML=true, $nr2br=true) {
        if (!$str) {
            return "";
        }

        if ($escapeHTML) {
            $str = htmlspecialchars($str);
        }

        $str = $this->quote($str);
        foreach($this->bbcode_table as $key => $val) {
            $str = preg_replace_callback($key, $val, $str);
        }

        if ($nr2br) {
            $str = preg_replace_callback("/\n\r?/", function ($match) { return "<br/>"; }, $str);
        }

        return $str;
    }
}