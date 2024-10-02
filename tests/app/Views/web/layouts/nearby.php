<!-- Check nearby -->

<div class="col-12" id="check-nearby-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Facility Around</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0001" class="form-check-input" checked>
                    <label for="F0001">Culinary Place</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0002" class="form-check-input">
                    <label for="F0002">Gazebo</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0003" class="form-check-input">
                    <label for="F0003">Outbond Field</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0004" class="form-check-input">
                    <label for="F0004">Parking Area</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0005" class="form-check-input">
                    <label for="F0005">Public Bathroom</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0006" class="form-check-input">
                    <label for="F0006">Selfie Area</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0007" class="form-check-input">
                    <label for="F0007">Souvenir Place</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0008" class="form-check-input">
                    <label for="F0008">Tree House</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0009" class="form-check-input">
                    <label for="F0009">Viewing Tower</label>
                </div>
            </div>
            <div class="form-check">
                <div class="checkbox">
                    <input type="checkbox" id="F0010" class="form-check-input">
                    <label for="F0010">Worship Place</label>
                </div>
            </div>
            <div class="mt-3">
                <label for="inputRadiusNearby" class="form-label">Radius: </label>
                <label id="radiusValueNearby" class="form-label">0 m</label>
                <input type="range" class="form-range" min="0" max="20" value="0" id="inputRadiusNearby" name="inputRadius" onchange="updateRadius('Nearby');">
            </div>
            <div class="mt-3">
                <a title="Close Nearby" class="btn icon btn-outline-primary mx-1" onclick="closeNearby()">
                    <i class="fa-solid fa-circle-xmark"></i> Close
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search result nearby -->
<div class="col-12" id="result-nearby-col">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title text-center">Search Result Facility Around</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive overflow-auto" id="table-result-nearby">
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0001">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0002">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0003">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0004">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0005">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0006">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0007">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0008">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0009">
                </table>
                <table class="table table-hover mb-md-5 mb-3 table-lg" id="table-F0010">
                </table>
            </div>
        </div>
    </div>
</div>