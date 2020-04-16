<!DOCTYPE html>
<html lang="pt-br">
	<head>

  <?php 
    // Meta
    $fontLink = '';
    include(locate_template( 'meta.php', false, false )); ?>

    <script type="text/javascript">
      // Ajax
	    // var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	    // var searchValue = "<?php echo get_search_query(); ?>";
	    // var numPagesStwo = <?php echo $wp_query->max_num_pages; ?>;
      // var whatAjax = 'search';
      // var catSlug = '';
  	</script>

		<?php wp_head(); ?>

	</head>
	<body>

