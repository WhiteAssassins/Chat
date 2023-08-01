<?php
// application/helpers/input_helper.php

if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('input_post')) {
    function input_post($index = null, $xss_clean = false) {
        $CI = get_instance();
        return $CI->input->post($index, $xss_clean);
    }
}

if (!function_exists('input_get')) {
    function input_get($index = null, $xss_clean = false) {
        $CI = get_instance();
        return $CI->input->get($index, $xss_clean);
    }
}
if (!function_exists('send_message')) {
    function send_message($sender, $message) {
        $CI = get_instance();
        if (!empty($sender) && !empty($message)) {
            $data = array(
                'sender' => $sender,
                'message' => $message
            );
            $CI->db->insert('chat_messages', $data);
        }
    }
}