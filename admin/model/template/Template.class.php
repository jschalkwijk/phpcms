<?php
// voormeerdere includes kan ikeen array doorgeven en dan met een loop de verschillden file paths includen.
// ipv elke pagina deze functie te laten oproepen kan ik ook met een class en method de url analyzeren,
// en op basis daarvan een method activeren met dezelfde naam alsde url,die dan de template functie.
//oproept om de pagina te genereren. zie MVC, application.php,home.php, zoek op Google, php route url templates.
class Template_Template {
	private $content;
	private $tpl_name = 'default';
	// takes an array with the file paths
	function __construct($page_title,$file_paths,$params,$data = []){
		$this->content = $file_paths;
		$tpl_name = $this->tpl_name;
		require_once('templates/'.$tpl_name.'/header.php');
		require_once('templates/'.$tpl_name.'/nav.php');
		require_once('templates/'.$tpl_name.'/content-top.php');
		
		foreach ($file_paths as $content){
			require_once('view/'.$content);
		}
		require_once('templates/'.$tpl_name.'/content-bottom.php');
		require_once('templates/'.$tpl_name.'/footer.php');
	}
}

?>