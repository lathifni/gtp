<!-- Check nearby -->
<div class="col-12" id="check-track-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Facility Along the Track</h5>
        </div>
        <div class="card-body">
            <?php foreach ($facility as $f) : ?>
                <div class="form-check">
                    <div class="checkbox">
                        <input type="checkbox" id="<?= esc($f['id']); ?>" class="form-check-input">
                        <label><?= esc($f['type']); ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="mt-3">
                <a title="Search" class="btn icon btn-outline-primary mx-1" id="inputTrackAlong" onclick="checkTrack()">
                    <i class="fa-solid fa-magnifying-glass-location"></i> Search
                </a>
                <a title="Close Nearby" class="btn icon btn-outline-primary mx-1" onclick="closeNearby()">
                    <i class="fa-solid fa-circle-xmark"></i> Close
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search result nearby -->
<div class="col-12" id="result-track-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Search Result</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive overflow-auto" id="table-result-nearby">
                <?php foreach ($facility as $f) : ?>
                    <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-<?= esc($f['id']); ?>">
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>