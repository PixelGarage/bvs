<?php
function hso_theme_alpha_preprocess_page(&$vars) {
  $contexts = context_active_contexts();
  if (array_key_exists('ks-college', $contexts) || array_key_exists('ks-college-home', $contexts) ||
      array_key_exists('ex-college', $contexts) || array_key_exists('ex-college-home', $contexts)) {
    $vars['logo'] = str_replace('logo_hmz.png', 'logo_hmz_hso.png', $vars['logo']);
  }
}