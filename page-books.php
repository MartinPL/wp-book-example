<?

$context = Timber::context();

$categories = get_terms( array(
	'taxonomy' => 'category',
	'hide_empty' => false,
) );
$categories[0]->name = 'All categories';
$categories[0]->term_id = 0;
$context['categories'] = $categories;


$colors = get_tags( array(
	'taxonomy' => 'post_tag',
	'hide_empty' => false,
) );
$allColorsItem = (object) array(
	'term_id' => 0,
	'name' => 'All colors'
);
array_unshift($colors , $allColorsItem);
$context['colors'] = $colors;

$context['currentURL'] = get_permalink($post);

$sortOptions = array(
	[
		'name' => 'last',
		'title' => 'Last added'
	],
	[
		'name' => 'author',
		'title' => 'Author family name'
	],
	[
		'name' => 'title',
		'title' => 'Book title'
	]
);
$context['sortOptions'] = $sortOptions;


$paged = get_query_var('paged');
$queryArgs = array(
	'post_type' => 'book',
	'posts_per_page' => 9,
	'tax_query' => $tax_query,
	'paged' => $paged
);

if(isSet($_GET['category']) && $_GET['category']) {
	$queryArgs['tax_query'][] = array(
		'taxonomy' => 'category',
		'terms' => $_GET['category'],
		'field' => 'id',              
	);
}

if(isSet($_GET['color']) && $_GET['color']) {
	$queryArgs['tax_query'][] = array(
		'taxonomy' => 'post_tag',
		'terms' => $_GET['color'],
		'field' => 'id',              
	);
}

if(isSet($_GET['sort']) && $_GET['sort']) {
	switch($_GET['sort']) {
		case 'author':
			$queryArgs['order'] = 'ASC';
			$queryArgs['meta_key'] = 'author';
			$queryArgs['orderby'] = 'lastMetaValueWord';
		break;
		case 'title':
			$queryArgs['orderby'] = 'title';
			$queryArgs['order'] = 'ASC';
		break;
	}
}

$books = new Timber\PostQuery($queryArgs);
$context['books'] = $books;
$context['pagination'] = $books->pagination(4);

Timber::render('page-books.twig', $context);

?>