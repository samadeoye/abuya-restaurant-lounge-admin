<?php
//will redirect user to login upon destroying session
$baseUrl = DEF_ROOT_PATH;

$additionalJsOnLoad[] = <<<EOQ

$.ajax({
    url: 'actions',
    type: 'POST',
    dataType: 'json',
    data: {
        'action': 'logout'
    },
    beforeSend: function() {
    },
    complete: function() {
    },
    success: function(data)
    {
        window.location.href = '{$baseUrl}/login';
    }
});

EOQ;

require_once DEF_PATH_PAGES . '/layout.php';