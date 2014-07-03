<?php

$pagekids = get_pages("sort_order=ASC&sort_column=menu_order");
//echo "Id = " + $pagekids[1]->ID;
//debug(reset($pagekids)->ID);
wp_redirect(get_permalink(reset($pagekids)->ID));
?>