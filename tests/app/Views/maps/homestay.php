<?= $this->extend('maps/main'); ?>

<?= $this->section('content') ?>
<style>
        /* Styling for the fixed button */
        .fixed-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 999; /* Make sure it's above other content */
            background-color: rgba(0, 0, 0, 0);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .fixed-button2 {
            position: fixed;
            bottom: 80px;
            left: 20px;
            z-index: 999; /* Make sure it's above other content */
            background-color: rgba(0, 0, 0, 0);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .fixed-button3 {
            position: fixed;
            bottom: 140px;
            left: 20px;
            z-index: 999; /* Make sure it's above other content */
            background-color: rgba(0, 0, 0, 0);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
</style>
<button class="fixed-button3">
        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Current Location" class="btn icon btn-primary mx-1" id="current-position" onclick="currentPosition();">
            <span class="material-symbols-outlined">my_location</span>
        </a></button>
<button class="fixed-button2">
        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Set Manual Location" class="btn icon btn-primary mx-1" id="manual-position" onclick="manualPosition();">
            <span class="material-symbols-outlined">pin_drop</span>
        </a>
</button>
<button class="fixed-button">
        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle Legend" class="btn icon btn-primary mx-1" id="legend-map" onclick="viewLegendMobile();">
            <span class="material-symbols-outlined">visibility</span>
        </a>
</button>
<?= $this->include('maps/map-body'); ?>

<script>currentUrl = "api";</script>
<?php

if (isset($data)):
    foreach ($data as $item): ?>
        <script>currentUrl = currentUrl + "<?= esc($item['id']); ?>"</script>
        <script>objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);</script>
<?php
    endforeach;?>
    <script>boundToObject();</script>
<?php
endif;

?>

<?= $this->endSection() ?>
