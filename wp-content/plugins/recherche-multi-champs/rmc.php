<?php
/*
Plugin Name: Recherche Multi-Champs
Plugin URI: https://wordpress.org/plugins/recherche-multi-champs/
Description: Créer vos propres champs pour vos articles/pages et proposer une recherche basée sur ces champs à vos visiteurs.
Version: 0.8.9
Author: CréaLion.NET
Author URI: https://crealion.net
*/
include_once plugin_dir_path( __FILE__ ).'/rmc_widget.php';
//
function rmc_install(){
	if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
    global $wpdb;
    $wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rmc_champs (id INT AUTO_INCREMENT PRIMARY KEY, cle VARCHAR(%d) NOT NULL, valeur TEXT, type_champs VARCHAR(3));", "255"));
	//var_dump($wpdb->show_errors()) ; exit( 0 ) ;
    $wpdb->query($wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}rmc_options (id INT AUTO_INCREMENT PRIMARY KEY, cle VARCHAR(%d) NOT NULL, valeur TEXT);", "255"));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'afficher_champs_articles', 'on'));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'afficher_champs_pages', 'on'));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'afficher_champs_vide', ''));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'couleur_bordure', '#DDDDDD'));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'epaisseur_bordure', '0'));
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (cle, valeur) VALUES (%s, %s)", 'afficher_resultats_champs_vides', ''));
}
function rmc_uninstall(){
    global $wpdb;
}
function rmc_delete_fields(){
	global $wpdb;
	$wpdb->query("DROP TABLE {$wpdb->prefix}rmc_champs");
	$wpdb->query("DROP TABLE {$wpdb->prefix}rmc_options");
}
function rmc_register_rmc_widget(){
	register_widget('rmc_widget');
}
function rmc_add_admin_menu(){
    $hook = add_menu_page('Recherche Multi-Champs', 'Recherche Multi-Champs', 'manage_options', 'recherche-multi-champs', 'rmc_menu_html');
	add_action('load-'.$hook, 'rmc_process_action');
}
function rmc_process_action(){
    if (isset($_POST['ajouter_champs'])) {
		global $wpdb;
		$nouveau_champs = sanitize_text_field($_POST['nouveau_champs']);
        $nouveau_champs = str_replace(" ","_", $nouveau_champs);
        $type_champs = sanitize_text_field($_POST['type_champs']);
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs WHERE cle = %s", $nouveau_champs));
        if (is_null($row)) {
            $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_champs (cle, type_champs) VALUES (%s, %s)", $nouveau_champs, $type_champs));
        }
    }
	if ((isset($_POST['supprimer_champs'])) && (isset($_POST['champs']))) {
		global $wpdb;
        $champs = $_POST['champs'];
		if (is_array($champs)){
			$inQuery = implode(',', array_fill(0, count($champs), '%d'));
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}rmc_champs WHERE id IN ($inQuery)", $champs));
		}
    }
	if (isset($_POST['enregistrer_options'])) {
		global $wpdb;
        if (isset($_POST['afficher_champs_articles'])){$afficher_champs_articles = substr(sanitize_text_field($_POST['afficher_champs_articles'][0]),0,3);}else{$afficher_champs_articles = "";}
        if (isset($_POST['afficher_champs_pages'])){$afficher_champs_pages = substr(sanitize_text_field($_POST['afficher_champs_pages'][0]),0,3);}else{$afficher_champs_pages = "";}
        if (isset($_POST['afficher_champs_vide'])){$afficher_champs_vide = substr(sanitize_text_field($_POST['afficher_champs_vide'][0]),0,3);}else{$afficher_champs_vide = "";}
        if (isset($_POST['afficher_resultats_champs_vides'])){$afficher_resultats_champs_vides = substr(sanitize_text_field($_POST['afficher_resultats_champs_vides'][0]),0,3);}else{$afficher_resultats_champs_vides = "";}
        $couleur_bordure = substr(sanitize_text_field($_POST['couleur_bordure']),0,7);
        $epaisseur_bordure = intval(sanitize_text_field($_POST['epaisseur_bordure']));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'afficher_champs_articles'", $afficher_champs_articles));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'afficher_champs_pages'", $afficher_champs_pages));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'afficher_champs_vide'", $afficher_champs_vide));
        
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'afficher_resultats_champs_vides'", $afficher_resultats_champs_vides));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'couleur_bordure'", $couleur_bordure));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}rmc_options SET valeur = %s WHERE cle = 'epaisseur_bordure'", $epaisseur_bordure));
    }
}
$options = array();
function getOptions(){
	global $options, $wpdb;
	if (sizeof($options) == 0){
		$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options ORDER BY %s", "cle")) ;
		foreach ($resultats as $cv) {
			$options[$cv->cle] = $cv->valeur;
		}
	}
	if (!isset($options['afficher_resultats_champs_vides'])){
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}rmc_options (id, cle, valeur) VALUES ('6', %s, %s)", 'afficher_resultats_champs_vides', ''));
		$options['afficher_resultats_champs_vides'] = '';
	}
	return $options;
}
function rmc_menu_html(){
	echo "<br><br><i><font color=red>IMPORTANT : Pour visualiser les champs lors de la création de vos articles ou pages, pensez à afficher les \"champs personnalisés\" dans \"Options de l'écran\" (En haut de la page lorsque vous rédigez votre article ou votre page)</font></i>";
    echo '<br><br><b>Pour bien démarrer :</b><br> - Créez les champs sur cette page<br> - Activez le widget<br> - Remplissez les champs dans vos articles / pages<br> - Insérez le shortcode [rmc_shortcode] à l\'endroit où vous voulez afficher les champs<br> - Insérez le shortcode [rmc_search_shortcode] à l\'endroit où vous voulez inclure le formulaire de recherche. <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pour choisir la taille du formulaire vous pouvez utiliser le paramètre size comme dans ces exemples : [rmc_search_shortcode size="50%"] ou [rmc_search_shortcode size="250px"]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pour désigner la page affichant les résultats, ajouter l\'attribut action comme par exemple: [rmc_search_shortcode action="https://monsite.com/resultat/"] (Attention: Shortcode à insérer également dans la page affichant les résultats)';
	echo '<h1>'.get_admin_page_title().'</h1>';
    echo '<hr><p><b>Liste des champs: </b><form method="post" action=""><input type="hidden" name="supprimer_champs" value="1"/>';
	global $wpdb;
	$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs ORDER BY %s", "cle"));
	foreach ($resultats as $cv) {
		echo "<div style='display:inline-block;border:rgb(218,218,218) 1px solid;padding:5px;margin:5px'><input type='checkbox' title='Supprimer' name='champs[]' value='".$cv->id."'> ".str_replace('\\','',str_replace("_", " ", $cv->cle))." (".substr($cv->type_champs,0,1).")</div>" ;
	}
	echo '</p>';
	submit_button("Supprimer le(s) champs sélectionné(s)");
	echo '</form>';
	echo '<hr><form method="post" action="">
		<input type="hidden" name="ajouter_champs" value="1"/>
		<table>
		<tr><td><label><b>Créer un champs: </b></label></td>
		<td><input type="text" name="nouveau_champs" value=""/></td></tr>
		<tr><td><label><b>Type: </b></label></td>
		<td><select name="type_champs"><option value="TEX">Texte</option><option value="NUM">Numérique</option></select></td></tr>
		</table>
		<br><b>Note:</b> Choisir le type "Numérique" pour permettre à vos visiteurs de faire une recherche >=, <= ou = à un nombre. <br>
		<i>Par exemple: Pour afficher tous les articles dont le champs "prix" est "<=" à la valeur "50€". 
		<br>La comparaison ne fonctionne pas si vous placez des lettres devant les chiffres, par exemple "$50".</i>
		';
	submit_button("Ajouter");
	echo '</form><hr>';
	$options = getOptions();
	echo '<b>Options: </b><br><br>
	<form method="post" action="">
		<input type="hidden" name="enregistrer_options" value="1"/>
		<table>
		<tr><td colspan="2"><input type="checkbox" name="afficher_champs_articles[]"';
	if ($options['afficher_champs_articles']){echo "checked";} 
	echo '> Afficher les champs dans les articles lors de l\'appel au shortcode [rmc_shortcode] </td></tr>
		<tr><td colspan="2"><input type="checkbox" name="afficher_champs_pages[]"';
	if ($options['afficher_champs_pages']){echo "checked";} 
	echo '> Afficher les champs dans les pages lors de l\'appel au shortcode [rmc_shortcode]</td></tr>
		<tr><td><input type="checkbox" name="afficher_champs_vide[]"';
	if ($options['afficher_champs_vide']){echo "checked";}
	echo '> Afficher les champs vides </td><td> </td></tr>
	<tr><td><input type="checkbox" name="afficher_resultats_champs_vides[]"';
	if ($options['afficher_resultats_champs_vides']){echo "checked";}
	echo '> Afficher les résultats des champs vides </td><td> </td></tr>
		<tr><td>Couleur des bordures </td><td> <input type="text" name="couleur_bordure" value="'.$options['couleur_bordure'].'"></td></tr>
		<tr><td>Epaisseur des bordures </td><td> <input type="text" name="epaisseur_bordure" value="'.$options['epaisseur_bordure'].'"></td></tr>
		</table>';
	submit_button("Enregistrer");
    echo '<hr></form>';
}
function rmc_insert_post($post_id) {
  if ((get_post_type($post_id) == 'post') || (get_post_type($post_id) == 'page')) {
	global $wpdb;
	$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs ORDER BY %s", "cle")) ;
	foreach ($resultats as $cv) {
		add_post_meta($post_id, $cv->cle, '', true);
	}
  }
  return true;
}
function rmc_insert_post2(){
	rmc_insert_post(get_the_ID());
}
function rmc_shortcode($atts){
	$post_type = get_post_type();
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options WHERE cle = %s", 'afficher_champs_articles'));
	$afficher_champs_articles = $row->valeur;
	$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options WHERE cle = %s", 'afficher_champs_pages'));
	$afficher_champs_pages = $row->valeur;
	$result = "";
	if ((($post_type == "post") && ($afficher_champs_articles == "on")) || (($post_type == "page") && ($afficher_champs_pages == "on"))){
		$options = getOptions();
		$custom_fields = array_change_key_case(get_post_custom());
		$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs ORDER BY %s", "cle")) ;
		foreach ($resultats as $cv){
			if ( isset($custom_fields[strtolower($cv->cle)][0]) ) {
				if (($options['afficher_champs_vide'] == true) || ($custom_fields[strtolower($cv->cle)][0] != ""))
				$result .= '<div><b>'.str_replace("_", " ", ucfirst($cv->cle)).':</b> '.ucfirst($custom_fields[strtolower($cv->cle)][0]).'</div>';
			}
		}
	}
	return $result;
}
function rmc_search_shortcode($atts){
	$size = "100%";
	if (isset($atts['size'])){
		if ((strlen($atts['size']) < 6) && ((strpos(strtolower($atts['size']),"px") !== false) || (strpos($atts['size'],"%") !== false)))
		$size = $atts['size'];
	}
	$action = "";
	if (isset($atts['action'])){
		$action = $atts['action'];
	}
	$echo = '<form id="rmc_form_id" class="rmc_form" action="'.$action.'" method="post" style="width:'.$size.'"><input type="hidden" name="search_rmc" value="1">';	
	global $wpdb;
	$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs ORDER BY %s", "cle")) ;
	foreach ($resultats as $cv) {
		$cle = str_replace('\\','',$cv->cle);
		$vals = array_unique(get_meta_values($cle));
		if (sizeof($vals) > 0){
			$width = "100%";
			if ($cv->type_champs == "NUM"){
				$width = "80%";
			}
			$echo .= '<label for="'."rmc_".$cv->id.'">'.str_replace("_"," ",$cle).' :</label>';
			$echo .= '<select id="'."rmc_".$cv->id.'" name="'."rmc_".$cv->id.'" style="width:'.$width.';margin-top:0px;margin-bottom: 10px"><option value="(rmc_tous)">Tous</option>';
			if ($cv->type_champs == "NUM"){
				sort($vals, SORT_NUMERIC);
			}else{
				sort($vals);
			}
			$options = getOptions();
			foreach ( $vals as $val ){
				$selected = "";
				if ((isset($_POST["rmc_".$cv->id]))&&(str_replace('\\','',$_POST["rmc_".$cv->id])) == $val){
					$selected = "selected";
				}
				$val2 = $val;
				if (($val2 == "") && ($options['afficher_champs_vide'])){
					$val2 = "(vide)";
				}
				if (($val2 != "")){
					$echo .= "<option value='".str_replace('"','&#34;',str_replace("'","&#39;",$val))."' ".$selected.">".$val2."</option>";
				}
			}
			$echo .= '</select>';
			if ($cv->type_champs == "NUM"){
				$selected_sup = "";
				$selected_inf = "";
				if ((isset($_POST["rmc_".$cv->id."_compare"]))&&($_POST["rmc_".$cv->id."_compare"] == "SUP")){
					$selected_sup = "selected";
				}
				if ((isset($_POST["rmc_".$cv->id."_compare"]))&&($_POST["rmc_".$cv->id."_compare"] == "INF")){
					$selected_inf = "selected";
				}
				$echo .= '<select name="'."rmc_".$cv->id.'_compare" style="float:left;margin-top:0px;width:20%"><option value="EGA">=</option><option value="SUP" '.$selected_sup.'>>=</option><option value="INF" '.$selected_inf.'><=</option></select>';
			}
			$echo .= '<br style="clear:both">';
		}
	}
	$tri = "dateDesc"; if ((isset($_POST['rmc_orderby007']))&&($_POST['rmc_orderby007'] != "")){$tri = sanitize_text_field($_POST['rmc_orderby007']);}
	$echo .= '<label for="rmc_orderby007">Tri :</label>
		<select name="rmc_orderby007" style="width:100%;margin-top:0px;margin-bottom: 10px">
			<option value="dateDesc" '.(($tri == "dateDesc")? 'selected':'').'>Du plus récent au plus ancien</option>
			<option value="dateAsc" '.(($tri == "dateAsc")? 'selected':'').'>Du plus ancien au plus récent</option>
			<option value="alpha" '.(($tri == "alpha")? 'selected':'').'>Ordre alphabétique (A..Z)</option>
			<option value="notAlpha" '.(($tri == "notAlpha")? 'selected':'').'>Ordre alphabétique inversé (Z..A)</option>
		<select><br style="clear:both">';
		$rpp = "10"; if ((isset($_POST['rmc_rpp']))&&($_POST['rmc_rpp'] != "")){$rpp = intval(sanitize_text_field($_POST['rmc_rpp']));}
		$echo .= '<label style="display:block" for="rmc_rpp">Résultats par page :</label>
			<select name="rmc_rpp" style="width:100%;margin-top:0px;margin-bottom: 10px">
				<option value="5" '.(($rpp == "5")? 'selected':'').'>5</option>
				<option value="10" '.(($rpp == "10")? 'selected':'').'>10</option>
				<option value="20" '.(($rpp == "20")? 'selected':'').'>20</option>
				<option value="30" '.(($rpp == "30")? 'selected':'').'>30</option>
				<option value="40" '.(($rpp == "40")? 'selected':'').'>40</option>
				<option value="50" '.(($rpp == "50")? 'selected':'').'>50</option>
			<select>
			<br>';
		$echo .= '<br style="clear:both">
		<input type="submit" value="Lancer la recherche" style="float:right;margin-bottom:20px"/><br style="clear:both">
		</form>';
	return $echo;
}
function rmc_results_before_content() {
	$custom_content = "";
	if ((isset($_POST['search_rmc']))){
			$pageaff = 1; if ((isset($_POST['pageaff']))&&($_POST['pageaff'] != "")){$pageaff = intval(sanitize_text_field($_POST['pageaff']));}
			$posts_per_page = get_option('posts_per_page');
			if ((isset($_POST['rmc_rpp']))&&($_POST['rmc_rpp'] != "")){$posts_per_page = intval(sanitize_text_field($_POST['rmc_rpp']));}
			if (($posts_per_page > 50) || ($posts_per_page == -1)){$posts_per_page = 50;}
			$tri = "dateDesc"; if ((isset($_POST['rmc_orderby007']))&&($_POST['rmc_orderby007'] != "")){$tri = sanitize_text_field($_POST['rmc_orderby007']);}
			if($tri == "dateDesc"){$orderby = "date"; $order = "DESC";}
			if($tri == "dateAsc"){$orderby = "date"; $order = "ASC";}
			if($tri == "alpha"){$orderby = "title"; $order = "ASC";}
			if($tri == "notAlpha"){$orderby = "title"; $order = "DESC";}
			//$myargs = array('orderby' => 'post_type',
			$myargs = array('orderby' => $orderby,
				'order' => $order,
				'posts_per_page' => $posts_per_page,
				'offset' => ($pageaff-1) * $posts_per_page,
				'meta_query' => array(
					'relation'		=> 'AND'
				),
				'post_type' => array( 'post', 'page' )
			);
			global $wpdb;
			$resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_champs ORDER BY %s", "cle")) ;
			$debug = "";
			$nbfiltre = 0;
			$keyfiltre = "";
			foreach ($resultats as $cv) {
				if (isset($_POST["rmc_".$cv->id])){
					$keyfiltre = str_replace('\\','',$cv->cle);
					$rmc_cv_id = sanitize_text_field($_POST["rmc_".$cv->id]);
					if ($rmc_cv_id != ""){
						if ($rmc_cv_id != "(rmc_tous)"){
							$type = "CHAR";
							if ($cv->type_champs == "NUM"){
								$type = "NUMERIC";
							}
							$compare = '=';
							if (isset($_POST["rmc_".$cv->id."_compare"])){
								$rmc_cv_id_compare = sanitize_text_field($_POST["rmc_".$cv->id."_compare"]);
								if ($rmc_cv_id_compare == "SUP"){
									$compare = ">=";
								}
								if ($rmc_cv_id_compare == "INF"){
									$compare = "<=";
								}
							}
							$myargs["meta_query"][] = array('key' => str_replace('\\','',$cv->cle),'value' => str_replace('\\','',$rmc_cv_id),'compare' => $compare, 'type' => $type);
							$nbfiltre++;
						}
					}
				}
			}
			if ($nbfiltre == 0){
				//$myargs["meta_query"][] = array('key' => $keyfiltre,'value' => "",'compare' => 'LIKE', 'type' => 'CHAR');
				unset($myargs["meta_query"]);
				// echo "<pre>";
				// print_r($myargs);
				// echo "</pre>";
			}
			//$mythe_query = new WP_Query( $myargs );
			$mythe_query = get_posts( $myargs );
			query_posts( '$myargs' );
			$result = 0;
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options WHERE cle = %s", 'couleur_bordure'));
			$couleur_bordure = $row->valeur;
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options WHERE cle = %s", 'epaisseur_bordure'));
			$epaisseur_bordure = $row->valeur;
			$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}rmc_options WHERE cle = %s", 'afficher_resultats_champs_vides'));
			$afficher_resultats_champs_vides = $row->valeur;
			$list = $debug.'<div style="margin: 20px 0 20px 0;border: '.$epaisseur_bordure.'px solid '.$couleur_bordure.';padding:10px"><div class="content-headline"><h1 class="entry-headline"><span class="entry-headline-text">Résultats de la recherche</span></h1></div>';
			//while ( $mythe_query->have_posts() ) : $mythe_query->the_post();
			foreach ( $mythe_query as $post ) : setup_postdata( $post );
				$custom_fields = array_change_key_case(get_post_custom($post->ID));
				//var_dump($custom_fields);
				//var_dump($resultats);
				$nofields = true;
				foreach ($resultats as $cv) {
					if (isset($custom_fields[strtolower($cv->cle)])){
						if ($custom_fields[strtolower($cv->cle)][0] != ""){
							$nofields = false;
							break;
						}
					}
				}
				if (($afficher_resultats_champs_vides) || (!$nofields)){
					$result++;
					//$new_content = "VOIR";
					//$new_content = strip_tags(strip_shortcodes(get_the_content()));
					$new_content = preg_replace('#\[[^\]]+\]#', '', get_the_excerpt($post->ID));
					//$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($mythe_query->post->ID), 'medium');
					$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');
					// $list .= '	<hr style="width:100%;border: '.$epaisseur_bordure.'px solid '.$couleur_bordure.';clear:both">
								// <article class="grid-entry" style="float: left;margin: 0 20px 20px 0;width: 100%;">
									// <a style="float:left;margin-right:10px;" href="' . get_permalink() . '"><img src="'.$feat_image[0].'"></a>
									// <span>
										// <a href="'. get_permalink() . '"><b>' . ucfirst(get_the_title()) . '</b></a> - 
										// <span>'.substr($new_content,0,400).'...</span>
									// </span>
								// </article>';
					$height = "";
					if ($epaisseur_bordure == 0){
						$height = "height:0px;";
					}
					$list .= '<hr style="width:100%;'.$height.'border: '.($epaisseur_bordure-1).'px solid '.$couleur_bordure.';clear:both;background-color: '.$couleur_bordure.'">';
					$list .= '<article class="grid-entry" style="float: left;margin: 0 20px 20px 0;width: 100%;">';
					$list .= '	<a style="float:left;margin-right:10px;" href="' . get_permalink($post->ID) . '">';
					$list .= '		<img src="'.$feat_image[0].'">';
					$list .= '	</a>';
					$list .= '	<span>';
					$list .= '		<a href="'. get_permalink($post->ID) . '">';
					$list .= '			<b>' . ucfirst($post->post_title) . '</b>';
					if (get_post_type($post->ID) != 'page'){
						$list .= '		- '.get_the_date('d/m/Y',$post->ID);
					}
					$list .= '			 - <span>'.substr($new_content,0,200).'...</span>';
					$list .= '		</a>';
					$list .= '	</span>';
					$list .= '</article>';
				}
			//endwhile;
			endforeach; 
			wp_reset_postdata();
			if ($result == 0){
				$list .= "Aucun résultat pour cette recherche, merci de réessayer avec moins de critères.";
			}
			$list .= '<br style="clear:both;"></div>';
			if ($pageaff > 1){
				$list .= '<div style="float:left"><form action="" method="post">';
				$list .= '<input type="hidden" name="search_rmc" value="1"><input type="hidden" name="pageaff" value="'.($pageaff - 1).'">';
				$list .= '<input type="hidden" name="rmc_orderby007" value="'.$tri.'">';
				$list .= '<input type="hidden" name="rmc_rpp" value="'.$posts_per_page.'">';
				foreach ($resultats as $cv) {
					if (isset($_POST["rmc_".$cv->id])){
						$rmc_cv_id = sanitize_text_field($_POST["rmc_".$cv->id]);
						if ($rmc_cv_id != ""){
							if ($rmc_cv_id != "(rmc_tous)"){
								$list .= '<input type="hidden" name="rmc_'.$cv->id.'" value="'.esc_attr($rmc_cv_id).'">';
							}
						}
						if (isset($_POST["rmc_".$cv->id."_compare"])){
							$rmc_cv_id_compare = sanitize_text_field($_POST["rmc_".$cv->id."_compare"]);
							$list .= '<input type="hidden" name="rmc_'.$cv->id.'_compare" value="'.esc_attr($rmc_cv_id_compare).'">';
						}
					}
				}
				$list .= '<input type="submit" value="Page précédente"></form></div>';
			}
			if ($result == $posts_per_page){
				$list .= '<div style="float:right"><form action="" method="post">';
				$list .= '<input type="hidden" name="search_rmc" value="1"><input type="hidden" name="pageaff" value="'.($pageaff + 1).'">';
				$list .= '<input type="hidden" name="rmc_orderby007" value="'.$tri.'">';
				$list .= '<input type="hidden" name="rmc_rpp" value="'.$posts_per_page.'">';
				foreach ($resultats as $cv) {
					if (isset($_POST["rmc_".$cv->id])){
						$rmc_cv_id = sanitize_text_field($_POST["rmc_".$cv->id]);
						if ($rmc_cv_id != ""){
							if ($rmc_cv_id != "(rmc_tous)"){
								$list .= '<input type="hidden" name="rmc_'.$cv->id.'" value="'.esc_attr($rmc_cv_id).'">';
							}
						}
						if (isset($_POST["rmc_".$cv->id."_compare"])){
							$rmc_cv_id_compare = sanitize_text_field($_POST["rmc_".$cv->id."_compare"]);
							$list .= '<input type="hidden" name="rmc_'.$cv->id.'_compare" value="'.esc_attr($rmc_cv_id_compare).'">';
						}
					}
				}
				$list .= '<input type="submit" value="Page suivante"></form></div>';
			}
			$list .= '<br style="clear:both;">';
			$custom_content .= $list;
			$custom_content = nl2br($custom_content);
			$custom_content = str_replace("\r","",$custom_content);
			$custom_content = str_replace("\n","",$custom_content);
			$custom_content = str_replace("'","&#39;",$custom_content);
			wp_reset_query();
			unset($_POST["search_rmc"]);
		//return $custom_content."<br><br><h1 class='entry-title'>$title</h1>";
		//echo $custom_content;
		echo "<script>
		var div = document.createElement('div');
		div.innerHTML = '$custom_content';
		var child = document.getElementById('rmc_form_id');
		if (child){
			child.parentNode.insertBefore(div, child);
		}else{
			child = document.getElementById('primary');
			if (!child){child = document.getElementById('content-wrap');}
			if (!child){child = document.getElementById('main');}
			if (!child){child = document.getElementById('content');}
			if (!child){
				child = document.getElementById('left-area');
				if (child){
					var i = 0;
					while((i <= child.childNodes.length) && (child.childNodes[i].nodeType != '1')){
						i++;
					}
					child.insertBefore(div, child.childNodes[1]);
				}
			}else{
				child.childNodes[0].parentNode.insertBefore(div, child.childNodes[0]);
			}
		}
		</script>";
	}
}
function get_meta_values( $key = '', $status = 'publish' ) {
	global $wpdb;
	if( empty( $key ) )
		return;
	$r = $wpdb->get_col( $wpdb->prepare( "
		SELECT pm.meta_value FROM {$wpdb->postmeta} pm
		LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		WHERE pm.meta_key = '%s' 
		AND p.post_status = '%s' 
		AND (p.post_type = 'post' OR p.post_type = 'page')
	", $key, $status ) );
	return $r;
}
//add_filter( 'wp_title', 'rmc_results_before_content_the_title',10,2);
//add_filter( 'the_title', 'rmc_results_before_content_the_title',10,2);
//add_filter( 'get_the_title', 'rmc_results_before_content_get_the_title',10,1);
add_action('wp_footer','rmc_results_before_content');
add_action('wp_insert_post', 'rmc_insert_post');
add_action('edit_form_after_editor', 'rmc_insert_post2');
register_activation_hook(__FILE__, 'rmc_install');
register_deactivation_hook(__FILE__, 'rmc_uninstall');
register_uninstall_hook(__FILE__, 'rmc_delete_fields');
add_action('widgets_init', 'rmc_register_rmc_widget');
add_action('admin_menu', 'rmc_add_admin_menu');
add_shortcode('rmc_shortcode', 'rmc_shortcode');
add_shortcode('rmc_search_shortcode', 'rmc_search_shortcode');
?>