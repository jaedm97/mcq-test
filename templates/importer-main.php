<?php
/**
 * Importer Main
 */


$html_content = mcq_test()->get_args_option( 'html_content', '', $_POST );
$html_content = stripslashes( $html_content );
$doc          = new DOMDocument();

libxml_use_internal_errors( true );
$doc->loadHTML( mb_convert_encoding( $html_content, 'HTML-ENTITIES', 'UTF-8' ) );

$xpath     = new DomXPath( $doc );
$nodeList  = $xpath->query( '//div[@class="show-question"]' );
$questions = array();

for ( $index = 0; $index < $nodeList->length; $index ++ ) {

	$this_node  = $nodeList->item( $index );
	$this_node  = $this_node->ownerDocument->saveHTML( $this_node );
	$nested_doc = new DOMDocument();

	$nested_doc->loadHTML( mb_convert_encoding( $this_node, 'HTML-ENTITIES', 'UTF-8' ) );

	$nested_xpath = new DomXPath( $nested_doc );
	$correct_node = $nested_xpath->query( '//li[@class="answer correct-answer"]' );
	$option_nodes = $nested_xpath->query( '//li[@class="answer"]' );
	$title_node   = $nested_doc->getElementsByTagName( 'strong' );
	$options      = array();

	if ( isset( $correct_node->item( 0 )->nodeValue ) ) {
		foreach ( $option_nodes as $option_node ) {
			$options[] = array(
				'value'      => trim( $option_node->nodeValue ),
				'is_correct' => false,
			);
		}
		$options[] = array(
			'value'      => trim( $correct_node->item( 0 )->nodeValue ),
			'is_correct' => true,
		);
	}

	if ( isset( $title_node->item( 0 )->nodeValue ) ) {
		shuffle( $options );
		$questions[] = array(
			'title'   => trim( $title_node->item( 0 )->nodeValue ),
			'options' => $options,
		);
	}
}

echo '<pre>';
print_r( $questions );
echo '</pre>';

?>

<form action="" method="post" accept-charset="utf-8">
    <textarea name="html_content" cols="30" rows="10"><?php echo $html_content; ?></textarea>
    <br><br>
    <button class="button button-primary" type="submit">Submit</button>
</form>
