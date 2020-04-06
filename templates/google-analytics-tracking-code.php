<?php
/**
 * Google Analytics tracking code.
 *
 * @link https://support.google.com/analytics/answer/1008080?hl=en
 */

?>

<!-- Google Analytics by Pronamic -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="<?php echo \esc_url( $url ); ?>">"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', <?php echo \wp_json_encode( $tracking_id ); ?>);
</script>

<!-- / Google Analytics by Pronamic -->
