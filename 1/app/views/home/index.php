<?php
// The layout adds <head>, the header and the footer; the header stays
// hidden while the hero is on screen and appears once you scroll past it.
foreach (['hero', 'about', 'journey', 'travel-map', 'vlogs', 'skills', 'blogs'] as $section) {
    echo view("home/{$section}");
}
