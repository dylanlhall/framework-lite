<?php

if (isset($_GET['config'])):

	if ($_GET['config'] == "all"):
	
		$fw = new FrameWork;
		
		foreach ($fw->content->pages as $id => $page):
		
			$fw->content->pages[$id]->href = PAGE . $page->uri;
			$fw->content->pages[$id]->anchor = $page->name;
		
		endforeach; unset($id, $pages);
		
		$result = $fw;
		
		$response = json_encode($result); unset($result);
	    print $response; unset($response);
		
		exit;
	
	endif;

endif;

class FrameWork {

    public $active;

    public function __construct() {
	
        $this->get_json_config();

        $this->set_definitions();
		
		$this->active = (object) array();
        $this->get_active_content();

    }
	
	public function do_tweaks($code) {
	
		$cmd = array("[!PAGE]", "[!BASE]", "[!CSS]", "[!ECMA]");
		$cmds = array("!PAGE", "!BASE", "!CSS", "!ECMA");
		
		$find = array("!PAGE:", "!BASE!", "!CSS:", "!ECMA:");
		$replace = array(PAGE, BASE, BASE . CSS, BASE . ECMA);
		
		$code = str_replace($find, $replace, $code);
		
		return $code;
	
	}

    public function get_root_dir() {

        $root = getcwd() . DIRECTORY_SEPARATOR;
        $root = str_replace(DIRECTORY_SEPARATOR, "/", $root);

        $result = $root; unset($root);
        return $result;

    }

    public function get_json_config() {
	
		$root = $this->get_root_dir();
		
        $config = (object) array();

            $config->json = (object) array();

                $config->json->file = $root . "common/json/framework.json";
                $config->json->code = file_get_contents($config->json->file);
                $config->json->data = json_decode($config->json->code);
		
        $result = $config->json->data; unset($config);
		
		$this->config = $result->config;
		$this->content = $result->content;
		
        $this->config->dir->ROOT = $this->get_root_dir();
	
    }

    private function set_definitions() {
	
		define("BASE", $this->config->dir->BASE);
        define("ROOT", $this->config->dir->ROOT);

        foreach ($this->config->dir->core as $key => $value):

            define($key, $value);

        endforeach; unset($key, $value);
		
		$page_query = $this->config->page_query;
		define("PAGE", BASE . "index.php?" . $page_query . "="); unset($page_query);

    }

    public function get_page_data($uri) {

        $pages = $this->content->pages;
		
        foreach ($pages as $id => $page):
		
            if ($page->uri == $uri):
			
                return $page;
			
            endif;
		
        endforeach;

    }

    private function get_active_content() {

        $page_query = $this->config->page_query;

        if (isset($_GET[$page_query])):

            $result = $this->get_page_data($_GET[$page_query]);

        else:

            $result = $this->get_default_content();

        endif;

        $this->active->page = $result; unset($result);

    }

    private function get_default_content() {

        $result = $this->get_page_data($this->config->opening_page);
        return $result;

    }

}

?>
