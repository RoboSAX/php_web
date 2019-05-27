<?php
setlocale(LC_CTYPE, 'de_DE@euro', 'de_DE', 'deu_deu');
class Content {

    private $tree=null;
    private $content_dir=null;
    private $current_page=null;
    function __construct($content_dir) {
        define("INVISIBLE","invisible");
        define("SHRINK","shrink");
        $this->content_dir=$content_dir;
    }

    function norm_str($str) {
        $str=mb_strtolower($str);       // lower case
        $str=str_replace(" ","_",$str); // replace " " with "_"

        $str=preg_replace("/[^a-zäüö0-9_\/]+/", "",$str);
          // mb_ereg_replace for multi-byte-string?
          // keep only: a-z  0-9  äüö  "_" and "/"
          // all others are removed

        return $str;
    }

    function get_file_folder_name($path) {
        $exploded=explode('/',$path);
        $res=$exploded[count($exploded)-1];

        if (strpos($res,'-')===1) // only support one digit for sorting
            $res=explode('-',$res,2)[1];

        $exploded=explode('.',$res);
        $res=$exploded[0];
        return $res;
    }

    function hasSuffix($path,$suffix) {
        $exploded=explode('.',$path);
        if (count($exploded)<2)
            return false;

        for($i=count($exploded)-1;$i>=0;$i--) {
            if ($exploded[$i]==$suffix)
                return true;
        }
        return false;
    }

    function create_php_ref($in) {
        $exploded=explode('/',$in);

        if (count($exploded)<2)
            return false;

        if ($exploded[0]===$this->content_dir) {
            unset($exploded[0]);
            $exploded=array_values($exploded);
        }

        for ($i=0;$i<count($exploded)/*-1*/;$i++) {
            $exploded[$i]=$this->get_file_folder_name($exploded[$i]);
        }

        return $this->norm_str(implode('/',$exploded));
    }

    function find_page($page) {
        $this->current_page=$this->find_page_in_tree($this->tree,$page);
        if ($this->current_page===false) {
            if (isset($this->tree["files"])&&(count($this->tree["files"])>0))
                $this->current_page=$this->tree["files"][0]; // or Error-Page
            else
                $this->current_page=null;
        }
        return $this->current_page;
    }

    function find_page_read_content($ref, $replace_external_src=false) {
        $content = file_get_contents($this->find_page($ref), FILE_USE_INCLUDE_PATH);

        if ($replace_external_src) {
            // Could be nicer ;-)
            $content = str_replace( " src= "    , " src="          , $content );
            $content = str_replace( " src ="    , " src="          , $content );
            $content = str_replace( ' src="http', ' data-src="http', $content );
            $content = str_replace( ' src="//'  , ' data-src="//'  , $content );
              // Optional: url without protocol
        }

        return $content;
    }

    function find_page_in_tree($tree,$page) {
        //print_r($tree);
        //echo "\n<br>$page";

        $exploded_page=explode('/',$page);
        if (count($exploded_page)===1) {
            if (($exploded_page[0]==="") && (count($tree["files"])>0))
                return $tree["files"][0];
            else if ($exploded_page[0]==="")
                return false; // index.inc.php ?

            foreach ($tree["files"] as $value) {
                if ($this->norm_str($this->get_file_folder_name($value))===$this->norm_str($exploded_page[0])) {
                    return $value;
                }
            }
        } else {
            foreach ($tree["dirs"] as $key => $value) {
                if ($this->norm_str($this->get_file_folder_name($key))===$this->norm_str($exploded_page[0])) {
                    $newpage="";
                    for($i=1;$i<count($exploded_page);$i++)
                        $newpage.= (($i===1)?"":"/") . $exploded_page[$i];
                    return $this->find_page_in_tree($tree["dirs"][$key],$newpage);
                }
            }
        }
        return false;
    }

    function scan_by_folder($folder) {
        $tree=array();
        $tree=array("files"=>glob("$folder/{*.html,*.htm,*.php}",GLOB_BRACE),"dirs"=>array());

        $dirs=glob("$folder/*",GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $tree["dirs"]=array_merge($tree["dirs"],array($dir=>$this->scan_by_folder($dir)));
        }

        return $tree;
    }

    function scan_content() {
        $this->tree=$this->scan_by_folder($this->content_dir);
        return $this->tree;
    }

    function create_head_navigation() {
        $tree=$this->tree;
        echo "<ul>";

        foreach ($tree["files"] as $value) {
            if ($this->hasSuffix($value,INVISIBLE))
                continue;

            $class="";
            if ($value===$this->current_page)
                $class .= ' class="active"';
            echo '<li><a href="?ref='.$this->create_php_ref($value).'"'."$class>".$this->get_file_folder_name($value).'</a></li>';
        }
        echo "</ul>";
    }


    function create_side_navigation() {
        $tree=$this->tree;
        foreach ($tree["dirs"] as $key => $dir_value) {
            if ($this->hasSuffix($key,INVISIBLE))
                continue;
            else if ($this->hasSuffix($key,SHRINK) && !in_array($this->current_page,$tree["dirs"][$key]["files"])) {
                echo '<ul class="submenu"><li>';
                echo '<a class="submenu" href="?ref=' . $this->create_php_ref($tree["dirs"][$key]["files"][0]) . '">' . $this->get_file_folder_name($key) . '</a>';
                echo '</li></ul>';
                continue;
            }

            echo '<ul class="submenu">';

            echo $this->get_file_folder_name($key);//."<br>";

            foreach ($tree["dirs"][$key]["files"] as $value) {
                if ($this->hasSuffix($value,INVISIBLE))
                    continue;

                $class="";
                if ($value===$this->current_page)
                    $class .= ' class="active"';
                echo '<li><a href="?ref='.$this->create_php_ref($value).'"'."$class>".$this->get_file_folder_name($value).'</a></li>';
            }
            echo "</ul>";
        }
    }
}
?>
