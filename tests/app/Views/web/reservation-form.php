<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
$reservationhomestay = in_array('reservationhomestay', $uri);
?>

<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('styles') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">
<style>
    .filepond--root {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Reservation Guide</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <b>Reservation of Tour Packages provided</b>
                         <li>Tourists choose a tour package from the input form (detailed package information can be seen on the tour package page)</li>
                         <li>Tourists fill out the reservation form for the desired tour package and/or homestay</li>
                     <br>
                     <b>Customized Tour Package Reservations</b>
                         <li>Tourists can select the 'custom package' button to create the desired package</li>
                         <li>Tourists are asked to fill in what activities, locations and services available in those activities</li>
                         <li>Tourists make a reservation by filling in the tour package reservation form</li>
                         <li>If tourists want to book a homestay, they can fill in the homestay reservation form</li>
                     <br>
                     <b>Homestay Reservation</b>
                         <li>If tourists only want to make a homestay reservation without a tour package, they can fill in the 'custom reservation' form </li>
                         <li>Tourists fill their activity day by selecting the desired homestay activity object</li>
                         <li>Then you can fill in the reservation form provided</li>
                     <br>
                     <b>Package Order</b>
                         <li>The minimum order quantity must meet the minimum capacity</li>
                         <li>If there is less than the minimum number of people then the price is calculated as 1 package</li>
                         <li>If there is more than the minimum number of 1 package, then if the additional <5 you pay plus half the package price, if >=5 you pay plus 1 package price, so for multiples of the minimum capacity</li>
                     <br>
                     <b>Reservation Payment</b>
                         <li>Tourists can choose the date and time to check in for the tour package</li>
                         <li>If you reserve a homestay, check-in and check-out of the homestay still starts at 12.00 noon </li>
                         <li>Tour package reservations can be submitted and then please wait for admin confirmation</li>
                         <li>If the admin approves, the deposit payment is 20% of the total reservation price and is paid a maximum of 2 days after the visit</li>
                         <li>Cancellations of reservations can be made up to 3 days after the visit, in this case the deposit paid will be returned</li>
                         <li><i><b>If cancellation is made after the 3rd day of the visit, the deposit will not be returned</i></b></li>
                         <li>Payment of the remainder of the deposit can be paid on the day of the tourist visit</li>
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
            <form class="form form-vertical" id="reservationForm" action="<?= ($edit) ? base_url('web/reservation/update') . '/' . $detail['id'] : base_url('web/reservation/create'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Package</h4>
                    </div>

                    <div class="card-body">
                        <div col=col-auto>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" data-bs-whatever="@getbootstrap"><i class="fa fa-info"></i><i>Read this guide</i></button>
                            <br>
                            <input type="checkbox" id="readCheckbox" name="readCheckbox" required>
                            <label for="readCheckbox"><i>I have read this guide</i></label>
                            <br> <br>
                        </div>
                        <?= csrf_field();  ?>
                        <div class="form-group">
                            <label for="package">Package</label>
                            <select id="package" name="package" class="form-control" required>
                                <option value="" selected>Select Package</option>
                                <?php foreach ($data as $item => $keyy) : ?>
                                    <?php if($edit): 
                                        if($detail['package_id']==$keyy['id']): ?>
                                            <option selected data-price="<?= esc($keyy['price']); ?>" data-day="<?= esc($keyy['days']); ?>" data-capacity="<?= esc($keyy['min_capacity']); ?>" value="<?= esc($detail['package_id']); ?>" ><?= esc($keyy['name']); ?></option>                                                                
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <option data-price="<?= esc($keyy['price']); ?>" data-day="<?= esc($keyy['days']); ?>" data-capacity="<?= esc($keyy['min_capacity']); ?>" value="<?= esc($keyy['id']); ?>" ><?= esc($keyy['name']); ?></option>                                                                
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            
                            </select>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label for="min_capacity">Minimal Capacity</label>
                                <input type="number" id="min_capacity" name="min_capacity" readonly value="<?= ($edit) ? $keyy['min_capacity'] : old('min_capacity'); ?>" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-5">
                                <label for="day">Day Activities</label>
                                <input type="number" id="day" name="day" readonly value="<?= ($edit) ? $keyy['day'] : old('day'); ?>" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label for="check_in">Check-in</label>
                                <input type="date" id="check_in" name="check_in" min="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="check_in"></label>
                                <input type="time" id="time_check_in" name="time_check_in" min="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label for="check_out">Check-out</label>
                                <input readonly type="date" id="check_out" name="check_out" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="check_out"></label>
                                <input readonly type="time" id="time_check_out" name="time_check_out" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_people">Total People</label>
                            <input type="number" id="total_people" name="total_people" value="<?= ($edit) ? $detail['total_people'] : old('total_people'); ?>" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="item">Package Order</label>
                            <input type="text" id="item" name="item" class="form-control"  readonly required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="note" class="mb-2">Note</label>
                            <textarea class="form-control" id="note" name="note" placeholder="Make requests that you want to be on the reservation record" required rows="4"><?= ($edit) ? $data['note'] : old('note'); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price Package</label>
                            <input type="text" id="price" name="price" class="form-control" value="<?= ($edit) ? $keyy['price'] : old('price'); ?>" readonly>
                        </div>
                         <div class="form-group">
                            <label for="total_price">Total Price Package</label>
                            <input type="text" id="total_price" name="total_price" class="form-control" value="<?= ($edit) ? $detail['total_price'] : old('total_price'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="deposit">Deposit</label>
                            <input type="text" id="deposit" name="deposit" class="form-control" value="<?= ($edit) ? $detail['deposit'] : old('deposit'); ?>" readonly>
                        </div>
                        <!-- <div class="form-group" id="homestayGroup" style="display: none;">
                            <input type="checkbox" id="agreeCheckbox" name="agreeCheckbox" required>
                            <label for="agreeCheckbox"><i>Untuk paket wisata ini akan diarahkan memesan homestay</i></label>
                        </div> -->
                        <?php if(!$edit): ?>
                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Save Reservation</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            
            </form>
        </div>

        <?php if($edit): ?>
            <div class="col-md-6 col-12" id="homestayGroup" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"><?= $title; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="col-auto ">
                            <br>
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#unitHomestayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Unit Homestay</button>
                            </div>
                        </div>

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Homestay</th>
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if (isset($booking)) : ?> 
                                <?php foreach ($booking as $dtb) : ?>
                                    <?php $i = 1; ?>                     
                                    <tr>
                                        <td><?= esc($i++); ?></td>
                                        <td>[<?= esc($dtb['name']); ?>] <?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                                        <td><?= esc($dtb['capacity']); ?></td>
                                        <td><?= esc($dtb['price']); ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <form action="<?= base_url('web/detailreservation/delete/').$dtb['date']; ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="date" value="<?= esc($dtb['date']); ?>">
                                                    <input type="hidden" name="homestay_id" value="<?= esc($dtb['homestay_id']); ?>">
                                                    <input type="hidden" name="unit_type" value="<?= esc($dtb['unit_type']); ?>">
                                                    <input type="hidden" name="unit_number" value="<?= esc($dtb['unit_number']); ?>">
                                                    <input type="hidden" name="reservation_id" value="<?= esc($dtb['reservation_id']); ?>">
                                                    <input type="hidden" name="description" value="<?= esc($dtb['description']); ?>">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-remove" aria-hidden="true"></i></button>
                                                </form>
                                            </div>
                                        </td> 
                                    </tr>              
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Total Price Homestay </td>
                                        <td>:   <?= 'Rp' . number_format(esc($price_home), 0, ',', '.'); ?></td>

                                    </tr>
                                    <tr>
                                        <td><hr> </td>
                                        <td><hr> </td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    <!-- modal add unit homestay -->
                        <div class="modal fade" id="unitHomestayModal" tabindex="-1" aria-labelledby="unitHomestayModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="unitHomestayModalLabel">Unit Homestay</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= base_url('web/detailreservation/create'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="reservation_id">Reservation</label>
                                                        <input type="text" class="form-control" id="reservation_id" name="reservation_id" readonly value="<?= esc($detail['id']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="pk_unit">Unit Homestay</label>
                                                    <select class="form-select" name="pk_unit" required>
                                                        <option value="" selected>Select Unit Homestay</option>
                                                        <?php foreach ($list_unit as $item => $keyy) : ?>
                                                            <option value="<?= esc($keyy['homestay_id']); ?>-<?= esc($keyy['unit_type']); ?>-<?= esc($keyy['unit_number']); ?>"> 
                                                                [<?= esc($keyy['name']); ?>] <?= esc($keyy['name_type']); ?> <?= esc($keyy['unit_number']); ?> <?= esc($keyy['nama_unit']); ?>
                                                            </option>                                                                
                                                        <?php endforeach; ?>                                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    <!-- end modal add unit homestay -->
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

<!--!!!!!!!!!!!! ini homestaynya bisa muncul button, muncul pas untuk edit aja !!!!!!!!!!!-->
            
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        $(document).ready(function () {
            $('#package').change(function () {
                calculateTotalPrice();
            });

            $('#total_people').change(function () {
                calculateTotalPrice();
            });

            $('#check_in, #time_check_in').change(function () {
                calculateCheckOut();
            });

            $('#unit_homestay').change(function () {
                calculateHomestayTotalPrice();
            });

            $('#addUnitButton').click(function () {
                // Implement your logic for adding homestay units here
            });

            calculateTotalPrice();
            calculateCheckOut();
        });

        function calculateTotalPrice() {
            const package = $('#package option:selected');
            const price = parseFloat(package.data('price'));
            const capacity = parseInt(package.data('capacity'));
            const totalPeople = parseInt($('#total_people').val());
            $('#price').val(price);
            $('#min_capacity').val(capacity);
            const numberOfPackages = Math.floor(totalPeople/capacity);
            const remainder = totalPeople%capacity; // Hitung sisa hasil bagi
            const batas= Math.ceil(capacity/2)

            if (numberOfPackages!=0) {
                 if (remainder != 0 && remainder<batas) {
                    const add = 0.5;
                    const order = numberOfPackages + add; // Tambahkan 0.5 jika sisa kurang dari 5
                    $('#item').val(order);

                    const totalPrice = price * order;
                    $('#total_price').val(totalPrice);
                    const deposit = totalPrice * 0.2;
                    $('#deposit').val(deposit);
                } else if (remainder>=batas) {
                    const add = 1;
                    const order = numberOfPackages + add; // Tambahkan 1 jika sisa lebih dari atau sama dengan 5
                    $('#item').val(order);

                    const totalPrice = price * order;
                    $('#total_price').val(totalPrice);
                    const deposit = totalPrice * 0.2;
                    $('#deposit').val(deposit);
                } else if (remainder==0){
                    const add = 0;
                    const order = numberOfPackages + add; 
                    $('#item').val(order);

                    const totalPrice = price * order;
                    $('#total_price').val(totalPrice);
                    const deposit = totalPrice * 0.2;
                    $('#deposit').val(deposit);
                } 
            } else { 
                const add = 1;
                const order = numberOfPackages + add;
                $('#item').val(order);

                const totalPrice = price * order;
                    $('#total_price').val(totalPrice);
                    const deposit = totalPrice * 0.2;
                    $('#deposit').val(deposit);
            }


            // Show or hide the homestay unit selection based on the package day value
            const day = parseInt(package.data('day'));
            $('#day').val(day);
            if (day > 1) {
                $('#homestayGroup').show();
            } else {
                $('#homestayGroup').hide();
            }
        }

        function calculateCheckOut() {
            const checkInDate = $('#check_in').val();
            const checkInTime = $('#time_check_in').val();
            const package = $('#package option:selected');
            const day = parseInt(package.data('day'));
            if (checkInDate && checkInTime) {
                const checkInDateTime = new Date(checkInDate + ' ' + checkInTime);
                const checkOutDateTime = new Date(checkInDateTime);
                
                if(day=='1' && checkInTime<'18:00:00'){
                    checkOutDateTime.setDate(checkOutDateTime.getDate());
                    const checkOutDate = checkOutDateTime.toISOString().split('T')[0];
                    const checkOutTime = checkOutDateTime.toTimeString().split(' ')[0];
                    $('#check_out').val(checkOutDate);
                    $('#time_check_out').val('18:00:00');
                } else if(day=='1' && checkInTime>'18:00:00'){
                    checkOutDateTime.setDate(checkOutDateTime.getDate() + day);
                    const checkOutDate = checkOutDateTime.toISOString().split('T')[0];
                    const checkOutTime = checkOutDateTime.toTimeString().split(' ')[0];
                    $('#check_out').val(checkOutDate);
                    $('#time_check_out').val('12:00:00');
                } else if(day>'1' ){
                    checkOutDateTime.setDate(checkOutDateTime.getDate() + day);
                    const checkOutDate = checkOutDateTime.toISOString().split('T')[0];
                    const checkOutTime = checkOutDateTime.toTimeString().split(' ')[0];
                    $('#check_out').val(checkOutDate);
                    $('#time_check_out').val('12:00:00');
                }
            } else {
                $('#check_out').val('');
                $('#time_check_out').val('');
            }
        }

    </script>

</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<?= $this->endSection() ?>


