<?php

function stats_activate() {
}

function stats_deactivate() {
}

register_activation_hook(STATS_FILE, 'stats_activate');
register_deactivation_hook(STATS_FILE, 'stats_deactivate');
