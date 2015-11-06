<?php
module_load_include('inc', 'webform', 'includes/webform.submissions');
$submission = webform_get_submission($node->nid, $sid);

// get course time node
$webform_components = $node->webform['components'];
foreach ($webform_components as $key => $data) {
	if ($data['form_key'] == 'course_times_nid') {
		$course_time_nid = $submission->data[$key]['value'][0];
		break;
	}
}
if ($course_time_nid) {
	$tracking_price = 0;
	$course_time_node = node_load($course_time_nid);
	if ($course_time_node && !empty($course_time_node->field_course)) {
		$course = node_load($course_time_node->field_course[LANGUAGE_NONE][0]['target_id']);
		$segment = !empty($course->field_segment) ? taxonomy_term_load($course->field_segment[LANGUAGE_NONE][0]['tid']) : FALSE;
		$location = !empty($course_time_node->field_location) ? node_load($course_time_node->field_location[LANGUAGE_NONE][0]['target_id']) : FALSE;
		$pdf_link = url('get_anmeldung/' . md5($sid . '2I7L1N1'));
		$tracking_price = $course_time_node->field_brutto_price[LANGUAGE_NONE][0]['value'];
	}
}
?>
<div class="webform-confirmation">
  <p>Besten Dank für Ihre Anmeldung.<p>
  <ul>
    <li>Die <a target="_blank" href="<?php print $pdf_link; ?>">Reservationsbestätigung (PDF)</a> können Sie nun herunterladen</li>
    <li>Per E-Mail erhalten Sie dieselbe Reservationsbestätigung in einigen Minuten zugestellt</li>
  </ul>

  <h2>Weiteres Vorgehen</h2>
  <p>Wir bitten Sie, spätestens 2 Tage vor der Prüfung vorbeizukommen und beim BVS Sekretariat für jedes
  	angemeldete Modul einen "Prüfungsgutschein ECDL" zu lösen und auszufüllen. Dieser Gutschein ist Ihr 
  	Eintritts-Ticket für die Prüfung.</p>

  <h2>Rückgängig machen</h2>
  <p>Wollen Sie Ihre Anmeldung rückgängig machen, nehmen Sie bitte telefonisch mit uns
  	Kontakt auf.</p>

  <p><a href="<?php print url('node/' . $course->nid); ?>">Zurück zur Übersicht Informatik ECDL</a></p>
</div>

<?php if ($course_time_nid): ?>
  <script type="text/javascript">
    // check existance (opt-out doesn't create ga)
    if (typeof ga == 'function') {
      // Load the ecommerce plug-in.
      ga('require', 'ecommerce');

      // add transaction
      ga('ecommerce:addTransaction', {
        'id': '<?php print $sid; ?>-<?php print $submission->remote_addr; ?>',    // Transaction ID. Required
        'affiliation': 'HSO <?php print addslashes($location->title); ?>',        // Affiliation or store name
        'revenue': '<?php print $tracking_price; ?>',                             // Grand Total
        'shipping': '0',                                                          // Shipping
        'tax': '0.0'                                                              // Tax
      });

      // add ecommerce item
      ga('ecommerce:addItem', {
        'id': '<?php print $sid; ?>-<?php print $submission->remote_addr; ?>',    // Transaction ID. Required
        'name': '<?php print addslashes($course->title); ?>',                     // Product name. Required
        'sku': '<?php print $course_time_node->field_internal_id[LANGUAGE_NONE][0]['value']; ?>', // SKU/code
        'category': '<?php print addslashes($segment->name); ?>',                 // Category or variation
        'price': '<?php print $tracking_price; ?>',                               // Unit price
        'quantity': '1'                                                           // Quantity
      });

      // submit transaction
      ga('ecommerce:send');      // Send transaction and item data to Google Analytics.
    }
  </script>
<?php endif; ?>
