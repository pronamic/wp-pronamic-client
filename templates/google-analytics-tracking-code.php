<?php
/**
 * Google Analytics tracking code.
 *
 * @link https://support.google.com/analytics/answer/1008080?hl=en
 * @link https://developers.google.com/gtagjs/devguide/configure
 */

$additional_config_info = (object) [
	/**
	 * IP addresses of hits sent to Google Analytics are anonymized
	 * by default in the Pronamic Client plugin.
	 *
	 * @link https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization
	 */
	'anonymize_ip' => true,
];

?>

<!-- Google Analytics by Pronamic -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="<?php echo \esc_url( $url ); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', <?php echo \wp_json_encode( $tracking_id ); ?>, <?php echo \wp_json_encode( $additional_config_info ); ?> );
</script>

<!-- / Google Analytics by Pronamic -->
