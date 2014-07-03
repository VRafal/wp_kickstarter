<?php
if (function_exists('add_image_size')) {
	add_image_size('gallery', 460, 280, array (
		'center',
		'center'
	));
	add_image_size('width140', 140);
	add_image_size('width200', 200);
	add_image_size('width300', 300);
	add_image_size('width700', 700);
	add_image_size('width1000', 1000);
	add_image_size('width1300', 1300);
}

function my_image_sizes($sizes) {
	$addsizes = array (
		"gallery" => __("Gallery picture"),
		"width140" => __("Image width 140"),
		"width200" => __("Image width 200"),
		"width300" => __("Image width 300"),
		"width700" => __("Image width 700"),
		"width1000" => __("Image width 1000"),
		"width1300" => __("Image width 1300")
	);
	$newsizes = array_merge($sizes, $addsizes);
	return $newsizes;
}
add_filter('image_size_names_choose', 'my_image_sizes');

// ????
// add_editor_style('css/normalize.css');
// add_editor_style('css/fonts.css');
// add_editor_style('css/main.css');
// add_editor_style('css/editor-style.css');

/**
 * Konfiguracja skórki.
 * Dodanie tłumaczenia dla skurki. Plik tłumaczeń znajdujący się w katalog_skórki/languages musi posiadać nazwę jezyka, np: pl_PL.po i pl_PL.mo.
 * Wywołanie tłumaczonego tekstu _e("Some text", 'moj_project');
 */
function moj_project_setup() {
	load_theme_textdomain('moj_project', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'moj_project_setup');

/**
 * Dodanie specyficznych styli dla konsoli wp
 */
function custom_color() {
	echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/admin.css" type="text/css" media="all" />';
}
add_action('admin_head', 'custom_color');

/**
 * Na potrzeby migracji na inny serwer.
 * Po przegraniu bazy, przy pierwszym uruchomieniu wejść do /wp-admin i zapisac jedną, dowolną stronę.
 * @TODO: Po opublikowaniu strony na live koniecznie zakomentować poniższy kod.
 */
switch ($_SERVER['SERVER_NAME']) {
	case 'moj_project.raf.dev':
		// update_option('siteurl','http://moj_project.raf.dev');
		// update_option('home','http://moj_project.raf.dev');
		break;
	case 'test.moj_project.pl':
		update_option('siteurl', 'http://test.moj_project.pl');
		update_option('home', 'http://test.moj_project.pl');
		break;
	case 'www.moj_project.pl':
		update_option('siteurl', 'http://www.moj_project.pl');
		update_option('home', 'http://www.moj_project.pl');
		break;
}

function custom_widgets_init() {
	register_sidebar(array (
		'name' => __('Header', 'moj_project'),
		'id' => 'sidebar-1',
		'description' => __('Appears on every pages', 'moj_project'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array (
		'name' => __('Page content', 'moj_project'),
		'id' => 'sidebar-2',
		'description' => __('Appears on every pages', 'moj_project'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array (
		'name' => __('Footer', 'moj_project'),
		'id' => 'sidebar-3',
		'description' => __('Appears on every pages', 'moj_project'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
}
add_action('widgets_init', 'custom_widgets_init');

/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets.
 * Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 */
function widget_first_last_classes($params) {
	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

	if (!$my_widget_num) { // If the counter array doesn't exist, create it
		$my_widget_num = array ();
	}

	if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if (isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id]++;
	}
	else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

	if ($my_widget_num[$this_id] == 1) { // If this is the first widget
		$class .= 'widget-first ';
	}
	elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;
}
add_filter('dynamic_sidebar_params', 'widget_first_last_classes');

/**
 *
 * @return zwraza url strony głównej z uwzględnieniem wersji językowej
 */
function getHomeURL() {
	return esc_url(bloginfo('url') . '/' . getShortLang());
}

/**
 *
 * @return oznaczenie aktualnej wersji jezykowej np.: 'pl', 'en'
 */
function getShortLang() {
	return substr(get_locale(), 0, 2);
}

/**
 * Return the post content.
 * VRB: Dodałem tą funkcję aby móc przetwarzać content przed wyświetleniem.
 * Funkcja the_content() drukowala content na stronie, nie zwracala go.
 *
 * @since 0.1
 *
 * @param string $more_link_text
 *        Optional. Content for when there is more text.
 * @param bool $strip_teaser
 *        Optional. Strip teaser content before the more text. Default is false.
 */
function get_content($more_link_text = null, $strip_teaser = false) {
	$content = get_the_content($more_link_text, $strip_teaser);

	/**
	 * Filter the post content.
	 *
	 * @since 0.71
	 *
	 * @param string $content
	 *        Content of the current post.
	 */
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

/**
 * Uatiwa wylistowanie zmiennej w czytelny sposób
 *
 * @param unknown $var
 */
function debug($var) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}