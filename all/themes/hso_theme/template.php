<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */
 
/**
 * Output a form element in plain text format.
 */
function hso_theme_webform_element_text($variables) {
  $element = $variables['element'];
  $value = $variables['element']['#children'];

  $output = '';
  $is_group = webform_component_feature($element['#webform_component']['type'], 'group');

  // Output the element title.
  if (isset($element['#title'])) {
    if ($is_group) {
      $output .= '--' . $element['#title'] . '--';
    }
    elseif (!in_array(drupal_substr($element['#title'], -1), array('?', ':', '!', '%', ';', '@'))) {
      $output .= $element['#title'] . ':';
    }
    else {
      $output .= $element['#title'];
    }
  }
  // Wrap long values at 65 characters, allowing for a few fieldset indents.
  // It's common courtesy to wrap at 75 characters in e-mails.
  if ($is_group && drupal_strlen($value) > 65) {
    $value = wordwrap($value, 65, "\n");
    $lines = explode("\n", $value);
    foreach ($lines as $key => $line) {
      $lines[$key] = '  ' . $line;
    }
    $value = implode("\n", $lines);
  }

  // Add the value to the output.
  if ($value) {
    $output .= (strpos($value, "\n") === FALSE ? ' ' : "\n") . $value;
  }

  // Indent fieldsets.
  if ($is_group) {
    $lines = explode("\n", $output);
    foreach ($lines as $number => $line) {
      if (strlen($line)) {
        $lines[$number] = '  ' . $line;
      }
    }
    $output = implode("\n", $lines);
  }

  if ($output) {
    $output .= "\n";
  }

  return $output;
}

/**
 * Process user picture without link for user-picture.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $account: A user, node or comment object with 'name', 'uid' and 'picture'
 *   fields.
 *
 * @see user-picture.tpl.php
 */
function hso_theme_preprocess_user_picture(&$variables) {
  $variables['user_picture'] = '';
  if (variable_get('user_pictures', 0)) {
    $account = $variables['account'];
    if (!empty($account->picture)) {
      // @TODO: Ideally this function would only be passed file objects, but
      // since there's a lot of legacy code that JOINs the {users} table to
      // {node} or {comments} and passes the results into this function if we
      // a numeric value in the picture field we'll assume it's a file id
      // and load it for them. Once we've got user_load_multiple() and
      // comment_load_multiple() functions the user module will be able to load
      // the picture files in mass during the object's load process.
      if (is_numeric($account->picture)) {
        $account->picture = file_load($account->picture);
      }
      if (!empty($account->picture->uri)) {
        $filepath = $account->picture->uri;
      }
    }
    elseif (variable_get('user_picture_default', '')) {
      $filepath = variable_get('user_picture_default', '');
    }
    if (isset($filepath)) {
      $alt = t("@user's picture", array('@user' => format_username($account)));
      // If the image does not have a valid Drupal scheme (for eg. HTTP),
      // don't load image styles.
      if (module_exists('image') && file_valid_uri($filepath) && $style = variable_get('user_picture_style', '')) {
        $variables['user_picture'] = theme('image_style', array('style_name' => $style, 'path' => $filepath, 'alt' => $alt, 'title' => $alt));
      }
      else {
        $variables['user_picture'] = theme('image', array('path' => $filepath, 'alt' => $alt, 'title' => $alt));
      }
    }
  }
}

/**
 * Preprocess Anmeldungs-panel pane (remote forms) to add the GUID (found in the URL) to the iFrame src attribute.
 *
 * @param $vars
 */
function hso_theme_preprocess_panels_pane(&$vars) {
  $pane = $vars['pane'];

  // process only RemoteForm pane
  if (isset($pane->configuration['admin_title']) && $pane->configuration['admin_title'] == "RemoteForm") {
    if (array_key_exists('id', $_GET)) {
      // add the GUID to the iFrame src attribute, if any
      $res = explode('src="', $vars['content']);
      $content = $res[0] . 'src="';
      $pos = strpos($res[1], '" ');
      $guid = "&id=" . $_GET['id'];
      $content .= substr_replace($res[1], $guid, $pos, 0);

      $vars['content'] = $content;
    }
  }

}

/**
 * Adds the profile id to the iFrame src attribute for the Kundenprofil remote form.
 *
 * @param $variables
 *
 * @return string
 */
function hso_theme_field__body__remote_form ($variables) {
  // adapt the body field for Kundenprofil
  $content = $variables['items'][0]['#markup'];
  if (strpos($content, 'if-kundenprofil') > 0) {
    // replace delivered profile id in content
    $profile_id =  (array_key_exists('id', $_GET)) ? $_GET['id'] : '';
    $variables['items'][0]['#markup'] = str_replace('%cid', $profile_id, $content);
  }

  // render the body field in the remote form content type
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}