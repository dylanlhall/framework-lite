<?php

error_reporting(E_ALL);

require_once "common/php/framework.php";
$fw = new Framework;

foreach ($fw->content->layout as $section):

    if ($section->content == "@main")
        $section = $fw->active->page;
	
	$page_query = $fw->config->page_query;
	
	if (is_null($section) OR ((count($_GET) > 0) AND !isset($_GET[$page_query]) AND (isset($section->uri))))
		$section = $fw->content->errors->{404};
	
	unset($page_query);
	
    if (preg_match("/[php]$/i", $section->content)):

        require_once ROOT . $section->content;

    elseif (preg_match("/[html]$/i", $section->content)):

        $html = file_get_contents(ROOT . $section->content);

        print $html; unset($html);

    elseif (preg_match("/[md|mdown]$/i", $section->content)):
	
		if (!isset($parsedown)):
		
            require_once ROOT . PHP . "parsedown.php";
            $parsedown = new Parsedown;
		
        endif;
		
        $md = file_get_contents($section->content);
        $html = $parsedown->text($md); unset($md);
		
        print $html; unset($html);
	
    endif;

endforeach; unset($id, $data);

?>
