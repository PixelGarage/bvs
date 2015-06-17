<?php
function hso_theme_alpha_preprocess_page(&$vars) {
  $contexts = context_active_contexts();
  if (array_key_exists('kv-college', $contexts)) {
    $vars['logo'] = str_replace('BVS_logo_all.png', 'BVS_berufswahl.png', $vars['logo']);
  }

  if (array_key_exists('hs-college', $contexts)) {
    $vars['logo'] = str_replace('BVS_logo_all.png', 'BVS_logo_all.png', $vars['logo']);
  }

  if (array_key_exists('ks-college', $contexts)) {
    $vars['logo'] = str_replace('BVS_logo_all.png', 'BVS_bildung.png', $vars['logo']);
  }

  if (array_key_exists('ex-college', $contexts)) {
    $vars['logo'] = str_replace('BVS_logo_all.png', 'BVS_logo_all.png', $vars['logo']);
  }

  if (array_key_exists('lang-college', $contexts)) {
    $vars['logo'] = str_replace('BVS_logo_all.png', 'BVS_sprachen.png', $vars['logo']);
  }
}